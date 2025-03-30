const data = {
    "starters": {
        "aperitif": [
            {
                "title": "Bruschetta",
                "desc": "Pain grillé avec tomates fraîches, ail, basilic et huile d'olive",
                "price": 8
            },
            {
                "title": "Tapenade",
                "desc": "Purée d'olives noires servie avec du pain croustillant",
                "price": 7
            },
            {
                "title": "Planche de charcuterie",
                "desc": "Assortiment de jambon cru, saucisson et pâté maison",
                "price": 15
            }
        ],
        "salades": [
            {
                "title": "Salade César",
                "desc": "Laitue romaine, poulet grillé, croûtons, parmesan et sauce César",
                "price": 12
            },
            {
                "title": "Salade Caprese",
                "desc": "Tomates fraîches, mozzarella di bufala, basilic et huile d'olive",
                "price": 11
            },
            {
                "title": "Salade Niçoise",
                "desc": "Thon, haricots verts, œufs durs, tomates, anchois et olives",
                "price": 13
            }
        ]
    },
    "mainFood": {
        "poisson": [
            {
                "title": "Saumon grillé",
                "desc": "Filet de saumon grillé servi avec une sauce citronnée et légumes vapeur",
                "price": 18
            },
            {
                "title": "Cabillaud à la provençale",
                "desc": "Cabillaud rôti avec une sauce tomate, olives et herbes de Provence",
                "price": 20
            },
            {
                "title": "Bouillabaisse",
                "desc": "Soupe de poissons de roche avec rouille et croûtons",
                "price": 24
            }
        ],
        "viandes": [
            {
                "title": "Entrecôte grillée",
                "desc": "Viande de bœuf tendre servie avec frites maison et sauce au poivre",
                "price": 22
            },
            {
                "title": "Magret de canard",
                "desc": "Magret rôti avec sauce au miel et purée de patates douces",
                "price": 21
            },
            {
                "title": "Côte de veau",
                "desc": "Côte de veau poêlée accompagnée de légumes de saison",
                "price": 25
            }
        ],
        "pâtes": [
            {
                "title": "Tagliatelles aux truffes",
                "desc": "Pâtes fraîches avec crème aux truffes et parmesan",
                "price": 19
            },
            {
                "title": "Lasagnes maison",
                "desc": "Lasagnes bolognaises gratinées au four",
                "price": 16
            },
            {
                "title": "Spaghetti alle vongole",
                "desc": "Spaghetti avec palourdes, ail, persil et vin blanc",
                "price": 17
            }
        ]
    },
    "desserts": {
        "glaces": [
            {
                "title": "Coupe trois parfums",
                "desc": "Vanille, chocolat, fraise avec chantilly",
                "price": 7
            },
            {
                "title": "Sorbet exotique",
                "desc": "Mangue, passion et citron vert",
                "price": 6
            }
        ],
        "pâtisseries": [
            {
                "title": "Tarte Tatin",
                "desc": "Tarte aux pommes caramélisées, servie tiède",
                "price": 8
            },
            {
                "title": "Mille-feuille",
                "desc": "Feuilletage croustillant et crème pâtissière à la vanille",
                "price": 9
            },
            {
                "title": "Fondant au chocolat",
                "desc": "Cœur coulant au chocolat noir et crème anglaise",
                "price": 10
            }
        ]
    },
    "drinks": {
        "softs": [
            {
                "title": "Coca-Cola",
                "desc": "Boisson gazeuse rafraîchissante",
                "price": 4
            },
            {
                "title": "Jus d'orange pressé",
                "desc": "Orange fraîchement pressée",
                "price": 5
            },
            {
                "title": "Eau minérale",
                "desc": "Plate ou gazeuse",
                "price": 3
            }
        ],
        "alcool": [
            {
                "title": "Vin rouge Bordeaux",
                "desc": "Grand cru AOC, 12 cl",
                "price": 6
            },
            {
                "title": "Champagne",
                "desc": "Brut prestige, 12 cl",
                "price": 10
            },
            {
                "title": "Mojito",
                "desc": "Cocktail à base de rhum, citron vert et menthe",
                "price": 9
            }
        ]
    }
};

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
    const tabsContent = document.querySelector(".tabs-content");
    const tabsButtons = document.querySelectorAll(".tabs-button");

    // Retire la classe .tabs-selected de tous les boutons
    tabsButtons.forEach(button => button.classList.remove("tabs-selected"));

    // Ajoute la classe .tabs-selected au bouton cliqué
    const activeButton = document.querySelector(`#tabs-${category}`);
    if (activeButton) {
        activeButton.classList.add("tabs-selected");
    }

    if (!data[category]) {
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

displayTabs("starters", data);