
function displayCalendarTimes(){
    const times = document.querySelector(".calendar-times");
    for (let i = 0; i < 24; i++) {
        if(i%2 === 0){
            times.innerHTML += `<span class="times-item">${i}:00</span>`;
        } else {
            times.innerHTML += `<span class="times-item"></span>`;
        }
    }
}

function displayEvent(data){
    data.forEach((item) => {
        const calItem = document.querySelector(`#cal-item-${item.id_days} > .calendar-item-content`);
        if(calItem.classList.contains('dayoff')){
            calItem.classList.remove('dayoff');
            calItem.innerHTML = ``;
        }
        let event = document.createElement("div");
        event.classList.add("cal-event");
        const timeStart = parseInt(item.time_start.split(":")[0]) + 1;
        const timeEnd = parseInt(item.time_end.split(":")[0]) + 1;
        event.style.gridRowStart = `${timeStart}`;
        event.style.gridRowEnd= `${timeEnd}`;
        event.innerHTML = `<p class="nb-places">Limite : ${item.number_places}</p>`
        calItem.appendChild(event);
    })
}

async function fetchOpeningBasic(){
    try {
        const response = await fetch(`/api/opening/basic`, {
            methode: "GET",
            headers : {'Content-Type': 'application/json'},
        });
        const dataJson = await response.json();
        if(response.ok){
            return dataJson;
        } else {

        }
    } catch (e){
        console.error(e);
    }
}
document.addEventListener('DOMContentLoaded', async () => {
    displayCalendarTimes();
    const data = await fetchOpeningBasic();
    displayEvent(data.result);
    console.log(data);
});

