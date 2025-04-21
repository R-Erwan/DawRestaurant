import {showBanner} from "../../popup/popup";
import {parseData} from "../../../js/utils";

document.addEventListener("DOMContentLoaded", async () => {
    const data = await fetchDishes();
    const dishes = await data.result;
    const parsedDishes = parseData(dishes);
    displayDishes(parsedDishes,'starters','all');
    displayCatSelect(parsedDishes,"starters");
    let activeCat = "starters";

    /* ===== Tabs btn ===== */
    const tabStarters = document.getElementById("tabs-starters");
    const tabMainFood = document.getElementById("tabs-mainFood");
    const tabDesserts = document.getElementById("tabs-desserts");
    const tabDrinks = document.getElementById("tabs-drinks");
    tabStarters.addEventListener("click", () => {
        toggleTabs(tabStarters);
        displayDishes(parsedDishes,"starters","all");
        displayCatSelect(parsedDishes,"starters");
        activeCat = "starters";
    })
    tabMainFood.addEventListener("click", () => {
        toggleTabs(tabMainFood);
        displayDishes(parsedDishes,"mainFood","all");
        displayCatSelect(parsedDishes,"mainFood");
        activeCat = "mainFood";
    })
    tabDesserts.addEventListener("click", () => {
        toggleTabs(tabDesserts);
        displayDishes(parsedDishes,"desserts","all");
        displayCatSelect(parsedDishes,"desserts");
        activeCat = "desserts";
    })
    tabDrinks.addEventListener("click", () => {
        toggleTabs(tabDrinks);
        displayDishes(parsedDishes,"drinks","all");
        displayCatSelect(parsedDishes,"drinks");
        activeCat = "drinks";
    })

    /* ===== Select sub-category ===== */
    document.querySelector('#sub-cat').addEventListener("change", (e) => {
        displayDishes(parsedDishes,activeCat,e.target.value);

    })

    /* ===== Add sub-category ===== */

    document.querySelector("#add-sub-cat").addEventListener("click", (e) => {
            const newCatNameInput = document.querySelector('#sub-cat-name');
        newCatNameInput.classList.toggle("hidden",false);
        const validNewCat = document.querySelector('#valid-sub-cat');
        validNewCat.classList.toggle("hidden",false);

        validNewCat.addEventListener('click', (e2) => {
            newCatNameInput.classList.toggle("hidden",true);
            validNewCat.classList.toggle("hidden",true);
            //TODO Fetch add new category
        });
    });

    console.log(parsedDishes);
})

async function fetchDishes() {
    try {
        const response = await fetch("/api/dish", {
            method: "GET",
            headers: {'Content-Type': 'application/json'},
        });
        const dataJson = await response.json();
        if(response.ok){
            return dataJson;
        } else {
            showBanner('error',"Failed to fetch dishes, try reloading the page ");
        }
    } catch (e){
        showBanner('error', "Failed to fetch dishes, try reloading the page : " + e);
    }
}

function displayDishes(dishes, category, subcategory) {
    const container = document.querySelector(".tabs-content");
    container.innerHTML = '';
    let toDisplay = dishes[category];

    Object.keys(toDisplay).forEach((subCat) => {
        if (subCat === subcategory || subcategory === 'all') {
            // Création du titre de sous-catégorie
            const subCatTitle = document.createElement("div");
            subCatTitle.classList.add("dish-item-cat");

            const h2 = document.createElement("h2");
            h2.textContent = subCat.charAt(0).toUpperCase() + subCat.slice(1);
            subCatTitle.appendChild(h2);

            container.appendChild(subCatTitle);

            // Création des éléments plats
            toDisplay[subCat].forEach(item => {
                if (item.id === null) return;
                const div = createItem(item.id,item.title,item.desc,item.price);
                container.appendChild(div);
            }); //END For each item

            const newDivBtn = document.createElement("button");
            newDivBtn.innerText = '+';
            newDivBtn.classList.add("new-div-btn");

            const newDiv = createItem(null,null,null,null,subCat,category);
            newDiv.classList.add("hidden");

            newDivBtn.addEventListener("click", (e) => {
                newDiv.classList.toggle("hidden",false);
                newDivBtn.classList.toggle("hidden",true);
            })

            container.appendChild(newDivBtn);
            container.appendChild(newDiv);
        }

    }); // END For each sub-cat
}

function createItem(id,title,desc,price,subCategory,category){
    const div = document.createElement("div");
    div.classList.add("dish-item");

    const inputTitle = document.createElement("input");
    inputTitle.setAttribute("type", "text");
    inputTitle.classList.add("item-input");
    inputTitle.setAttribute("placeholder","Nom du plat");
    if(title != null){
        inputTitle.classList.add("disabled");
        inputTitle.readOnly = true;
        inputTitle.value = title;
    }
    div.appendChild(inputTitle);

    const textarea = document.createElement("textarea");
    textarea.setAttribute("cols", "30");
    textarea.setAttribute("rows", "2");
    textarea.classList.add("item-input");
    textarea.setAttribute("placeholder","Description");
    if(desc != null){
        textarea.classList.add("disabled");
        textarea.readOnly = true;
        textarea.innerText = desc;
    }
    div.appendChild(textarea);

    const inputPrice = document.createElement("input");
    inputPrice.setAttribute("type", "number");
    inputPrice.setAttribute("min", "0");
    inputPrice.setAttribute("placeholder","Prix");
    inputPrice.classList.add("item-input");
    if(price != null){
        inputPrice.classList.add("disabled");
        inputPrice.readOnly = true;
        inputPrice.value = price;
    }
    div.appendChild(inputPrice);

    if(id !== null){
        const button = document.createElement("button");
        button.classList.add("update-btn");
        button.innerText = "Modifier";
        div.appendChild(button);

        const trash = document.createElement("i");
        trash.classList.add("fa-solid","fa-trash-can");
        div.appendChild(trash);

        button.addEventListener("click", async (e) => {
            if (button.classList.contains("update-btn")) {
                div.querySelectorAll(".item-input").forEach(item => {
                    item.readOnly = false;
                    item.classList.toggle("disabled",false);
                })
                button.classList.remove("update-btn");
                button.classList.add("valid-btn")
                button.innerText = "Valider"
            } else {
                const res = await fetchUpdate(id, inputTitle.value, textarea.value, inputPrice.value);
                div.querySelectorAll(".item-input").forEach(item => {
                    item.readOnly = true;
                    item.classList.toggle("disabled",true);
                })
                button.classList.add("update-btn");
                button.classList.remove("valid-btn")
                button.innerText = "Modifier";
            }
        });

        trash.addEventListener("click", async (e) => {
            const res = fetchDelete(id);
        });
    } else {
        const button = document.createElement("button");
        button.classList.add("new-btn");
        button.innerText = "Ajouter";
        div.appendChild(button);
        button.addEventListener("click", async (e) => {
            const res = await fetchPost(inputTitle.value, textarea.value, inputPrice.value,subCategory,category);
        });
        const space = document.createElement("span");
        space.classList.add("spacer");
        div.appendChild(space);
    }
    return div;
}

function toggleTabs(tab){
    const tabsButtons = document.querySelectorAll(".tabs-button");

    // Retire la classe .tabs-selected de tous les boutons
    tabsButtons.forEach(button => button.classList.remove("tabs-selected"));
    tab.classList.add("tabs-selected");
}

function displayCatSelect(dishes, category) {
    const subCatSelect = document.querySelector('#sub-cat');
    const catDishes = dishes[category];
    subCatSelect.innerHTML = '<option value="all" selected>Tous</option>';
    Object.keys(catDishes).forEach((subCat) => {
        const subCatStr = subCat.charAt(0).toUpperCase() + subCat.slice(1)
        subCatSelect.innerHTML += `<option value="${subCatStr}">${subCatStr}</option>`;
    })
}

async function fetchUpdate(id,title,desc,price){
    console.log(id,title,desc,price);
    // Todo
}

async function fetchDelete(id){
    console.log(id);
    // Todo
}

async function fetchPost(title,desc,price,subcategory,category){
    console.log(title,desc,price,subcategory,category);
    // Todo
}
