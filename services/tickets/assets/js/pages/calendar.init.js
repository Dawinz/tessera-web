/**
 * FullCalendar demo init - no-op unless the calendar page DOM is present.
 */
document.addEventListener("DOMContentLoaded", function () {
	if (
		typeof bootstrap === "undefined" ||
		typeof FullCalendar === "undefined" ||
		!document.getElementById("calendar") ||
		!document.getElementById("event-modal") ||
		!document.getElementById("form-event")
	) {
		return;
	}

	var n = new bootstrap.Modal(document.getElementById("event-modal"), { keyboard: false });
	var t = document.getElementById("modal-title");
	var a = document.getElementById("form-event");
	var l = null;
	var d = null;
	var i = document.getElementsByClassName("needs-validation");
	var e = new Date();
	var s = e.getDate();
	var o = e.getMonth();
	e = e.getFullYear();

	var external = document.getElementById("external-events");
	if (external && FullCalendar.Draggable) {
		new FullCalendar.Draggable(external, {
			itemSelector: ".external-event",
			eventData: function (el) {
				return {
					title: el.innerText,
					start: new Date(),
					className: el.getAttribute("data-class")
				};
			}
		});
	}

	var events = [
		{ title: "All Day Event", start: new Date(e, o, 1), className: "bg-primary" },
		{ title: "Long Event", start: new Date(e, o, s - 5), end: new Date(e, o, s - 2), className: "bg-warning" },
		{ id: 999, title: "Repeating Event", start: new Date(e, o, s - 3, 16, 0), allDay: false, className: "bg-info" },
		{ id: 999, title: "Repeating Event", start: new Date(e, o, s + 4, 16, 0), allDay: false, className: "bg-primary" },
		{ title: "Meeting", start: new Date(e, o, s, 10, 30), allDay: false, className: "bg-success" },
		{ title: "Lunch", start: new Date(e, o, s, 12, 0), end: new Date(e, o, s, 14, 0), allDay: false, className: "bg-danger" },
		{ title: "Birthday Party", start: new Date(e, o, s + 1, 19, 0), end: new Date(e, o, s + 1, 22, 30), allDay: false, className: "bg-success" },
		{ title: "Click for Google", start: new Date(e, o, 28), end: new Date(e, o, 29), url: "http://google.com/", className: "bg-dark" }
	];

	var calEl = document.getElementById("calendar");

	function r(ev) {
		n.show();
		a.classList.remove("was-validated");
		a.reset();
		l = null;
		t.innerText = "Add Event";
		d = ev;
	}

	function c() {
		if (window.innerWidth >= 768 && window.innerWidth < 1200) return "timeGridWeek";
		if (window.innerWidth <= 768) return "listMonth";
		return "dayGridMonth";
	}

	var m = new FullCalendar.Calendar(calEl, {
		timeZone: "local",
		editable: true,
		droppable: true,
		selectable: true,
		initialView: c(),
		themeSystem: "bootstrap",
		headerToolbar: {
			left: "prev,next today",
			center: "title",
			right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
		},
		windowResize: function () {
			m.changeView(c());
		},
		eventClick: function (info) {
			n.show();
			a.reset();
			l = info.event;
			document.getElementById("event-title").value = l.title;
			document.getElementById("event-category").value = l.classNames[0];
			d = null;
			t.innerText = "Edit Event";
		},
		dateClick: function (info) {
			r(info);
		},
		events: events
	});
	m.render();

	a.addEventListener("submit", function (ev) {
		ev.preventDefault();
		var title = document.getElementById("event-title").value;
		var category = document.getElementById("event-category").value;
		if (i[0] && i[0].checkValidity() === false) {
			i[0].classList.add("was-validated");
			return;
		}
		if (l) {
			l.setProp("title", title);
			l.setProp("classNames", [category]);
		} else if (d) {
			m.addEvent({ title: title, start: d.date, allDay: d.allDay, className: category });
		}
		n.hide();
	});

	var delBtn = document.getElementById("btn-delete-event");
	if (delBtn) {
		delBtn.addEventListener("click", function () {
			if (l) {
				l.remove();
				l = null;
				n.hide();
			}
		});
	}
	var newBtn = document.getElementById("btn-new-event");
	if (newBtn) {
		newBtn.addEventListener("click", function () {
			r({ date: new Date(), allDay: true });
		});
	}
});
