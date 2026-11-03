document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("calendar");
  var eventModalEl = document.getElementById("event-modal");
  var formEvent = document.getElementById("form-event");
  if (!calendarEl || !eventModalEl || !formEvent || typeof FullCalendar === "undefined") {
    return;
  }

  var modal = new bootstrap.Modal(eventModalEl, { keyboard: false });
  var modalTitle = document.getElementById("modal-title");
  var selectedEvent = null;
  var selectedDate = null;
  var validationForms = document.getElementsByClassName("needs-validation");
  var externalEvents = document.getElementById("external-events");
  var now = new Date();
  var day = now.getDate();
  var month = now.getMonth();
  var year = now.getFullYear();

  if (externalEvents) {
    new FullCalendar.Draggable(externalEvents, {
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

  var demoEvents = [
    { title: "All Day Event", start: new Date(year, month, 1), className: "bg-primary" }
  ];

  function openModal(info) {
    modal.show();
    formEvent.classList.remove("was-validated");
    formEvent.reset();
    selectedEvent = null;
    if (modalTitle) modalTitle.innerText = "Add Event";
    selectedDate = info;
  }

  function pickView() {
    if (window.innerWidth >= 768 && window.innerWidth < 1200) return "timeGridWeek";
    if (window.innerWidth <= 768) return "listMonth";
    return "dayGridMonth";
  }

  var calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: "local",
    editable: true,
    droppable: true,
    selectable: true,
    initialView: pickView(),
    themeSystem: "bootstrap",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
    },
    windowResize: function () {
      calendar.changeView(pickView());
    },
    dateClick: openModal,
    events: demoEvents
  });
  calendar.render();

  formEvent.addEventListener("submit", function (e) {
    e.preventDefault();
    if (validationForms[0] && validationForms[0].checkValidity() === false) {
      validationForms[0].classList.add("was-validated");
      return;
    }
    modal.hide();
  });

  var deleteBtn = document.getElementById("btn-delete-event");
  if (deleteBtn) {
    deleteBtn.addEventListener("click", function () {
      if (selectedEvent) {
        selectedEvent.remove();
        selectedEvent = null;
        modal.hide();
      }
    });
  }

  var newBtn = document.getElementById("btn-new-event");
  if (newBtn) {
    newBtn.addEventListener("click", function () {
      openModal({ date: new Date(), allDay: true });
    });
  }
});
