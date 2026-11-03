/**
 * Safe Dashonic form-advanced init - only touches elements that exist on the page.
 * (Template demo selectors must not throw on operational Tessera pages.)
 */
document.addEventListener("DOMContentLoaded", function () {
	function isChoiceEl(el) {
		if (!el) return false;
		var tag = (el.tagName || "").toLowerCase();
		if (tag === "select") return true;
		if (tag === "input") {
			var t = (el.getAttribute("type") || "text").toLowerCase();
			return t === "text" || t === "hidden" || !el.getAttribute("type");
		}
		return false;
	}

	if (typeof Choices !== "undefined") {
		document.querySelectorAll("[data-trigger]").forEach(function (el) {
			if (!isChoiceEl(el)) return;
			try {
				new Choices(el, {
					placeholderValue: "Select...",
					searchPlaceholderValue: "Search..."
				});
			} catch (e) { /* skip bad nodes */ }
		});

		[
			"#choices-single-no-search",
			"#choices-single-no-sorting",
			"#choices-multiple-remove-button",
			"#choices-multiple-groups",
			"#choices-text-remove-button",
			"#choices-text-unique-values",
			"#choices-text-disabled"
		].forEach(function (sel) {
			var el = document.querySelector(sel);
			if (!isChoiceEl(el)) return;
			try {
				new Choices(el, { removeItemButton: true });
			} catch (e) { /* skip */ }
		});
	}

	if (typeof Pickr !== "undefined") {
		[
			{ el: ".classic-colorpicker", theme: "classic", default: "#038edc" },
			{ el: ".monolith-colorpicker", theme: "monolith", default: "#5fd0f3" },
			{ el: ".nano-colorpicker", theme: "nano", default: "#f7cc53" }
		].forEach(function (cfg) {
			if (!document.querySelector(cfg.el)) return;
			try {
				Pickr.create({
					el: cfg.el,
					theme: cfg.theme,
					default: cfg.default,
					components: {
						preview: true,
						opacity: true,
						hue: true,
						interaction: { hex: true, input: true, clear: true, save: true }
					}
				});
			} catch (e) { /* skip */ }
		});
	}

	if (typeof flatpickr === "undefined") return;
	[
		"#datepicker-basic",
		"#datepicker-datetime",
		"#datepicker-humanfd",
		"#datepicker-minmax",
		"#datepicker-disable",
		"#datepicker-multiple",
		"#datepicker-range",
		"#datepicker-timepicker",
		"#datepicker-inline"
	].forEach(function (sel) {
		if (!document.querySelector(sel)) return;
		try {
			if (sel === "#datepicker-datetime") {
				flatpickr(sel, { enableTime: true, dateFormat: "m-d-Y H:i" });
			} else if (sel === "#datepicker-range") {
				flatpickr(sel, { mode: "range" });
			} else if (sel === "#datepicker-multiple") {
				flatpickr(sel, { mode: "multiple", dateFormat: "Y-m-d" });
			} else if (sel === "#datepicker-timepicker") {
				flatpickr(sel, { enableTime: true, noCalendar: true, dateFormat: "H:i" });
			} else if (sel === "#datepicker-inline") {
				flatpickr(sel, { inline: true });
			} else {
				flatpickr(sel);
			}
		} catch (e) { /* skip */ }
	});
});
