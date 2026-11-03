#!/usr/bin/env python3
"""Rewrite legacy XAMPP DB hosts to sr_mysqli() / env helpers (deploy-time safety net)."""
from __future__ import annotations

import re
from pathlib import Path

ROOT = Path("/var/www/html")

PAT_MYSQLI = re.compile(
    r'mysqli_connect\(\s*[\'"](?:localhost|127\.0\.0\.1)[\'"]\s*,\s*[\'"]root[\'"]\s*,\s*[\'"]Teccross@360[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]\s*\)'
)
PAT_NEW_MYSQLI = re.compile(
    r'new\s+mysqli\(\s*[\'"](?:localhost|127\.0\.0\.1)[\'"]\s*,\s*[\'"]root[\'"]\s*,\s*[\'"]Teccross@360[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]\s*\)'
)

changed = 0
for path in ROOT.rglob("*.php"):
    if "test" in path.parts or "vendor" in path.parts:
        continue
    try:
        text = path.read_text(encoding="utf-8", errors="ignore")
    except OSError:
        continue
    new = text
    new, n1 = PAT_MYSQLI.subn(r"sr_mysqli('\1')", new)
    new, n2 = PAT_NEW_MYSQLI.subn(r"sr_mysqli('\1')", new)
    n = n1 + n2
    if n and "sr_env.php" not in new:
        # Best-effort include for tickets tree
        if new.lstrip().startswith("<?php"):
            new = re.sub(
                r"^<\?php\s*",
                "<?php\nrequire_once __DIR__ . '/includes/sr_env.php';\n",
                new,
                count=1,
            )
    if n:
        path.write_text(new, encoding="utf-8")
        changed += n

print(f"Patched {changed} legacy DB host reference(s).")
