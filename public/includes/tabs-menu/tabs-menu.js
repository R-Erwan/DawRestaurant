async function fetchDishes(){
    try {
        const response = await fetch(`/api/dish`,{
            method: "GET",
            headers: {'Content-Type': 'application/json'}
        });
        const dataJson = await response.json();
        if (response.ok) {
            return dataJson;
        } else {
            //Page d'erreur
        }
    } catch (e) {
        console.error(e);
    }
}

function parseData(data) {
    const structured = {};

    data.forEach(item => {
        const category = item.category_name;
        const subcategory = item.subcategory_name;

        if (!structured[category]) {
            structured[category] = {};
        }

        if (!structured[category][subcategory]) {
            structured[category][subcategory] = [];
        }

        structured[category][subcategory].push({
            title: item.name,
            desc: item.description,
            price: parseFloat(item.price)
        });
    });
    return structured;
}

let data;

document.addEventListener('DOMContentLoaded', async () => {
    console.log("ok");
    const datas = await fetchDishes();
    const plats = datas.result;
    data = parseData(plats);
    displayTabs("starters", data);
});

const tabsStarters = document.querySelector("#tabs-starters")
const tabsMainFood = document.querySelector("#tabs-mainFood")
const tabsDesserts = document.querySelector("#tabs-desserts")
const tabsDrinks = document.querySelector("#tabs-drinks")

tabsStarters.onclick = function(){
    displayTabs("starters",data);
}
tabsMainFood.onclick = function(){
    displayTabs("mainFood",data);
}
tabsDesserts.onclick = function(){
    displayTabs("desserts",data);
}
tabsDrinks.onclick = function(){
    displayTabs("drinks",data);
}

function displayTabs(category,data){
    const tabsContent = document.querySelector(".tabs-content");
    const tabsButtons = document.querySelectorAll(".tabs-button");

    // Retire la classe .tabs-selected de tous les boutons
    tabsButtons.forEach(button => button.classList.remove("tabs-selected"));

    // Ajoute la classe .tabs-selected au bouton cliqué
    const activeButton = document.querySelector(`#tabs-${category}`);
    if (activeButton) {
        activeButton.classList.add("tabs-selected");
    }

    if(!data[category]){
        console.error("Category not found");
        return;
    }

    tabsContent.innerHTML = "";
    Object.keys(data[category]).forEach(subCategory => {
        // Ajout du titre de la sous-catégorie
        const subCategoryTitle = document.createElement("div");
        subCategoryTitle.classList.add("tabs-item-title");
        subCategoryTitle.textContent = subCategory.charAt(0).toUpperCase() + subCategory.slice(1);
        tabsContent.appendChild(subCategoryTitle);

        // Ajout des items de la sous-catégorie
        data[category][subCategory].forEach(item => {
            const tabItem = document.createElement("div");
            tabItem.classList.add("tab-item");

            const title = document.createElement("div");
            title.classList.add("title");
            title.textContent = item.title;

            const desc = document.createElement("div");
            desc.classList.add("desc");
            desc.innerHTML = `${item.desc} <span class="price">${item.price}€</span>`;

            tabItem.appendChild(title);
            tabItem.appendChild(desc);
            tabsContent.appendChild(tabItem);
        });
    });
}