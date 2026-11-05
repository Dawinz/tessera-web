#!/usr/bin/env python3
"""Apply naming corrections via mysql CLI. Idempotent. Works local + production."""
from __future__ import annotations

import os
import subprocess
import sys

TABLE_RENAMES = [
    ("rout_points", "route_points"),
    ("route_tranist", "route_transit"),
    ("tranist", "transit"),
    ("accout_transaction", "account_transaction"),
    ("roles_assiged", "roles_assigned"),
    ("manifest_entires", "manifest_entries"),
    ("job_card_entires", "job_card_entries"),
    ("vehcile_category", "vehicle_category"),
    ("mainfest_code", "manifest_code"),
    ("maintance", "maintenance"),
    ("pety", "petty"),
    ("constat", "constant"),
]

COLUMN_MAP = {
    "rout_id": "route_id",
    "ammount": "amount",
    "ammount_dollar": "amount_dollar",
    "ammount_remain": "amount_remain",
    "ammount_left": "amount_left",
    "ammount_curr": "amount_curr",
    "ammount_paid": "amount_paid",
    "ammount_status": "amount_status",
    "stock_ammount": "stock_amount",
    "insert_ammount": "insert_amount",
    "income_ammount_dollar": "income_amount_dollar",
    "l_ammount": "l_amount",
    "ammout": "amount",
    "payed": "paid",
    "payed_remain": "paid_remain",
    "payed_gross": "paid_gross",
    "payed_dollar_gross": "paid_dollar_gross",
    "l_payed": "l_paid",
    "buss": "bus",
    "quanitity": "quantity",
    "issur": "issuer",
    "tranist": "transit",
    "tranist_route_name": "transit_route_name",
    "tranist_route_id": "transit_route_id",
    "depature_id": "departure_id",
    "arraval": "arrival",
    "arraival": "arrival",
    "deperture": "departure",
    "adress_1": "address_1",
    "adress_2": "address_2",
    "scaned": "scanned",
    "fair_price": "fare_price",
    "fair_price_international": "fare_price_international",
    "boostrap": "bootstrap",
    "arrangment": "arrangement",
    "luggages_no": "luggage_no",
}


def mysql_bin() -> list[str]:
    for cand in (
        os.environ.get("MYSQL_BIN"),
        "/Applications/XAMPP/xamppfiles/bin/mysql",
        "mysql",
    ):
        if not cand:
            continue
        if cand == "mysql" or os.path.isfile(cand):
            return [cand]
    return ["mysql"]


def run_sql(db: str, sql: str) -> str:
    host = os.environ.get("MYSQLHOST") or os.environ.get("SR_DB_HOST") or "127.0.0.1"
    port = os.environ.get("MYSQLPORT") or os.environ.get("SR_DB_PORT") or "3306"
    user = os.environ.get("MYSQLUSER") or os.environ.get("SR_DB_USER") or "root"
    password = (
        os.environ.get("MYSQLPASSWORD")
        or os.environ.get("SR_DB_PASS")
        or os.environ.get("MYSQL_PWD")
        or ""
    )
    # Local XAMPP default
    if not password and host in ("127.0.0.1", "localhost"):
        password = "Teccross@360"
    cmd = mysql_bin() + [
        "-h",
        host,
        "-P",
        str(port),
        "-u",
        user,
        f"-p{password}",
        "--protocol=TCP",
        "-N",
        "-e",
        sql,
        db,
    ]
    env = os.environ.copy()
    p = subprocess.run(cmd, capture_output=True, text=True)
    if p.returncode != 0:
        err = (p.stderr or p.stdout or "").replace(password, "***")
        raise RuntimeError(err.strip() or "mysql failed")
    return (p.stdout or "").strip()


def apply_db(db: str) -> None:
    try:
        run_sql(db, "SELECT 1")
    except Exception as e:
        print(f"015 {db}: skip ({e})")
        return
    print(f"015 {db}: start")
    for old, new in TABLE_RENAMES:
        try:
            exists_old = run_sql(
                db,
                f"SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='{old}'",
            )
            exists_new = run_sql(
                db,
                f"SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='{new}'",
            )
            if exists_old == "1" and exists_new == "0":
                run_sql(db, f"RENAME TABLE `{old}` TO `{new}`")
                print(f"  table {old} -> {new}")
        except Exception as e:
            print(f"  table fail {old}: {e}")

    cols = "','".join(COLUMN_MAP.keys())
    listing = run_sql(
        db,
        "SELECT CONCAT(table_name,'|||',column_name,'|||',column_type,'|||',is_nullable) "
        f"FROM information_schema.columns WHERE table_schema=DATABASE() AND column_name IN ('{cols}')",
    )
    for line in listing.splitlines():
        if not line.strip():
            continue
        parts = line.split("|||")
        if len(parts) < 4:
            continue
        table, col, ctype, nullable = parts[0], parts[1], parts[2], parts[3]
        new = COLUMN_MAP.get(col)
        if not new:
            continue
        has_new = run_sql(
            db,
            f"SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='{table}' AND column_name='{new}'",
        )
        if has_new != "0":
            continue
        null_sql = "NULL" if nullable == "YES" else "NOT NULL"
        try:
            run_sql(db, f"ALTER TABLE `{table}` CHANGE `{col}` `{new}` {ctype} {null_sql}")
            print(f"  {table}.{col} -> {new}")
        except Exception as e:
            print(f"  FAIL {table}.{col}: {e}")
    print(f"015 {db}: done")


def main() -> int:
    dbs = sys.argv[1:] or [
        os.environ.get("SR_DB_NAME") or "ticket",
        os.environ.get("SR_CARGO_DB") or "cargo",
        os.environ.get("SR_ACCOUNT_DB") or "account",
    ]
    for db in dbs:
        apply_db(db)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
