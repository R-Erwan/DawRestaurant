document.addEventListener('DOMContentLoaded', () => {
    const calEl = document.querySelector(".calendarExc")
    const calendar = new FullCalendar.Calendar(calEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        dateClick : function (info) {
            alert(info);
       }
    })
    calendar.render()
})