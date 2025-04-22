import {showBanner} from "../../popup/popup";
import {parseData} from "../../../js/utils";

document.addEventListener("DOMContentLoaded", async () => {
    const data = await fetchDishes();
    const categories = await fetchCategories();
    const parsedDishes = parseData(categories,data);

    displayDishes(parsedDishes,'starters','all');
    displayCatSelect(parsedDishes,"starters");

    let activeCat = "starters";
    const categoryMap = {"starters":1,"mainFood":2,"desserts":3,"drinks":4}

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

    const newCatNameInput = document.querySelector('#sub-cat-name');
    const validNewCat = document.querySelector('#valid-sub-cat');

    document.querySelector("#add-sub-cat").addEventListener("click", () => {
        newCatNameInput.classList.remove("hidden");
        validNewCat.classList.remove("hidden");
    });

    validNewCat.addEventListener('click', () => {
        const name = newCatNameInput.value;
        newCatNameInput.classList.add("hidden");
        validNewCat.classList.add("hidden");
        fetchNewCategory(categoryMap[activeCat],name);
    });

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
    console.log(dishes);
    const container = document.querySelector(".tabs-content");
    container.innerHTML = '';

    dishes.forEach((categoryItem) => {
        // Filtre que la catégorie demandée
        if(categoryItem.category_name !== category) {
            return ;
        }

        // Filtre la sous-catégorie ou alors 'all'
        categoryItem.subcategories.forEach((subcategoryItem) => {
            if(subcategoryItem.subcategory_name !== subcategory && subcategory !== 'all') {
                return;
            }

            // Sous-catégorie
            const subCatTitle = document.createElement("div");
            subCatTitle.classList.add("dish-item-cat");

            const h2 = document.createElement("h2");
            h2.textContent = subcategoryItem.subcategory_name.charAt(0).toUpperCase() + subcategoryItem.subcategory_name.slice(1);
            subCatTitle.appendChild(h2);

            container.appendChild(subCatTitle);

            // Plats de la sous-catégorie
            subcategoryItem.dishes.forEach((dishItem) => {
                if(dishItem.id === null) return;
                const div = createItem(dishItem.id,dishItem.name,dishItem.description,dishItem.price);
                container.appendChild(div);
            })

            const newDivBtn = document.createElement("button");
            newDivBtn.innerText = '+';
            newDivBtn.classList.add("new-div-btn");

            const newDiv = createItem(null,null,null,null,subcategoryItem.subcategory_id);
            newDiv.classList.add("hidden");

            newDivBtn.addEventListener("click", (e) => {
                newDiv.classList.toggle("hidden",false);
                newDivBtn.classList.toggle("hidden",true);
            });

            container.appendChild(newDivBtn);
            container.appendChild(newDiv);
        });
    });
}

function createItem(id,title,desc,price,subCategoryId){
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
                const res = await fetchUpdate(id, inputTitle.value, textarea.value, inputPrice.value,subCategoryId);
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
            const res = await fetchPost(inputTitle.value, textarea.value, inputPrice.value,subCategoryId);
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

function displayCatSelect(parsedData, category) {
    const subCatSelect = document.querySelector('#sub-cat');
    subCatSelect.innerHTML = '<option value="all" selected>Tous</option>';
    parsedData.forEach((categoryItem) => {
        if(categoryItem.category_name === category){
            categoryItem.subcategories.forEach(subCategory => {
                const subCatStr = subCategory.subcategory_name;
                subCatSelect.innerHTML += `<option value="${subCatStr}">${subCatStr}</option>`;
            });
        }
    });
}

async function fetchUpdate(id,title,desc,price,subcategoryId){
    const jwt = localStorage.getItem('jwt');
    try {
        const result = await fetch("/api/dish", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${jwt}`
            },
            body: JSON.stringify({
                "id" : id,
                "name" : title,
                "description" : desc,
                "price" : price,
                "subcategory_id" : subcategoryId,
            })
        });
        const dataJson = await result.json();
        if(result.ok){
            showBanner('success',"Plat ajouté ");
        } else {
            showBanner('error', "Erreur lors de l'ajout : " + dataJson.message);
        }

    } catch (error) {
        showBanner('error',"Erreur lors de l'ajout : " + error);
    }
}

async function fetchDelete(id){
    const jwt = localStorage.getItem('jwt');
    try {
        const result = await fetch("/api/dish?dish_id="+id, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${jwt}`
            }
        });
        const dataJson = await result.json();
        if(result.ok){
            showBanner('success',"Plat supprimé");
        } else {
            showBanner('error', "Erreur lors de la suppression : " + dataJson.message);
        }

    } catch (error) {
        showBanner('error',"Erreur lors de la suppression : " + error);
    }
}

async function fetchPost(title,desc,price,subcategoryId,){
    const jwt = localStorage.getItem('jwt');
    try {
        const result = await fetch("/api/dish", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${jwt}`
            },
            body: JSON.stringify({
                "name" : title,
                "price" : price,
                "subcategory_id" : subcategoryId,
                "description" : desc,
            })
        });
        const dataJson = await result.json();
        if(result.ok){
            showBanner('success',"Plat ajouté ");
        } else {
            showBanner('error', "Erreur lors de l'ajout : " + dataJson.message);
        }

    } catch (error) {
        showBanner('error',"Erreur lors de l'ajout : " + error);
    }
}

async function fetchNewCategory(categoryId,name){
    const jwt = localStorage.getItem('jwt');
    try {
        const result = await fetch("/api/dish/subcategory", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${jwt}`
            },
            body: JSON.stringify({
                "name" : name,
                "category_id" : categoryId
            })
        });
        const dataJson = await result.json();
        if(result.ok){
            showBanner('success',"Catégorie créer");
        } else {
            showBanner('error', "Erreur lors de la création de la catégorie: " + dataJson.message);
        }

    } catch (error) {
        showBanner('error',"Erreur lors de la création de la catégorie : " + error);
    }
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
