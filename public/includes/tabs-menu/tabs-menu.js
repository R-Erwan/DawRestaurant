import {parseData} from "../../js/utils";
import {showBanner} from "../popup/popup";

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

let data;

document.addEventListener('DOMContentLoaded', async () => {
    const datas = await fetchDishes();
    const categories = await fetchCategories();
    data = parseData(categories,datas);
    displayTabs("starters", data);
});

const tabsStarters = document.querySelector("#tabs-starters")
const tabsMainFood = document.querySelector("#tabs-mainFood")
const tabsDesserts = document.querySelector("#tabs-desserts")
const tabsDrinks = document.querySelector("#tabs-drinks")

tabsStarters.onclick = function () {
    displayTabs("starters", data);
}
tabsMainFood.onclick = function () {
    displayTabs("mainFood", data);
}
tabsDesserts.onclick = function () {
    displayTabs("desserts", data);
}
tabsDrinks.onclick = function () {
    displayTabs("drinks", data);
}

function displayTabs(category, data) {
    console.log(data);
    const tabsContent = document.querySelector(".tabs-content");
    const tabsButtons = document.querySelectorAll(".tabs-button");

    // Retire la classe .tabs-selected de tous les boutons
    tabsButtons.forEach(button => button.classList.remove("tabs-selected"));

    // Ajoute la classe .tabs-selected au bouton cliqué
    const activeButton = document.querySelector(`#tabs-${category}`);
    if (activeButton) {
        activeButton.classList.add("tabs-selected");
    }

    const filteredData = data.find(cat => cat.category_name === category);

    tabsContent.innerHTML = "";
    filteredData.subcategories.forEach(subCategory => {
        if(subCategory.dishes.length > 0){
            const subCategoryTitle = document.createElement("div");
            subCategoryTitle.classList.add("tabs-item-title");
            subCategoryTitle.textContent = subCategory.subcategory_name;
            tabsContent.appendChild(subCategoryTitle);
        }

        subCategory.dishes.forEach((item) => {
            const tabItem = document.createElement("div");
            tabItem.classList.add("tab-item");

            const title = document.createElement("div");
            title.classList.add("title");
            title.textContent = item.name;

            const desc = document.createElement("div");
            desc.classList.add("desc");
            desc.innerHTML = `${item.description} <span class="price">${item.price}€</span>`;

            tabItem.appendChild(title);
            tabItem.appendChild(desc);
            tabsContent.appendChild(tabItem);
        });
    });

}

async function fetchCategories(){
    try {
        const response = await fetch("/api/dish/subcategory", {
            method: "GET",
            headers: {'Content-Type': 'application/json'},
        });
        const dataJson = await response.json();
        if(response.ok){
            return dataJson;
        } else {
            showBanner('error',"Failed to fetch : "+dataJson.message);
        }
    } catch (e){
        showBanner('error', "Failed to fetch : " + e);
    }
}