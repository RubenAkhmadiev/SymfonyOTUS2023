//Initialization constants
const removeBasketClassName = "remove-basket",
      apiUrl = "http://127.0.0.1:8019",
      perPage = 6
const telegram = window.Telegram.WebApp;

//Initialization variables
let productIds = [],
    sumProduct = 0,
    page = 0

document.addEventListener('DOMContentLoaded', function() {
  getProducts();
  pay();
  finishOrder();
  backPage();
  nextPage();
});

let countProductsValue = 0,
  clickProducts = function() {
    let productButtons = document.querySelectorAll(".menu .item-menu button");

    productButtons.forEach(item => {
      item.addEventListener("click", function() {

        if (item.classList.contains(removeBasketClassName)) {
          removeProduct(item);
        } else {
          addProduct(item);
        }

        item.classList.toggle(removeBasketClassName);

        if (countProductsValue > 10) {
          alert("Sorry, we can go you only ten products!");
          countProductsValue = 0;
        }
        if (countProductsValue < 0) {
          alert("Продуктов не может быть меньше 0!");
          countProductsValue = 0;
        }

        let countProductsValueElement = document.getElementById("countProductsValue");
        countProductsValueElement.innerHTML = countProductsValue;
        let sumProductElem = document.getElementById("sumProducts");
        sumProductElem.innerHTML = sumProduct;
      });
    });
  };

let addProduct = function(item) {
    item.innerHTML = "Убрать";
    countProductsValue += 1;
    sumProduct += parseInt(item.parentElement.getElementsByClassName('item-menu-price')[0].innerHTML.slice(5));
    productIds.push(item.parentElement.getElementsByClassName('item-menu-title')[0].getAttribute('data-id'));
    console.log(productIds.toString());
  },
  removeProduct = function(item) {
    item.innerHTML = "Добавить";
    countProductsValue -= 1;
    sumProduct -= parseInt(item.parentElement.getElementsByClassName('item-menu-price')[0].innerHTML.slice(5));
    removeItemFromArrayProducts(item.parentElement.getElementsByClassName('item-menu-title')[0].getAttribute('data-id'));
    console.log(productIds.toString());
  },
  removeItemFromArrayProducts = function(id) {
    let index = productIds.indexOf(id);
    if (index !== -1) {
      productIds.splice(index, 1);
    }
}



let getProducts = async function() {
  let response = await fetch(`/telegram/products?page=${page}&perPage=${perPage}`);

  if (response.ok) {
    let products = await response.json();
    console.log(products);
    products.forEach((item, index) => {
      if (index < 6) {
        showProduct(item);
      }
    });
    // document.getElementById("menu").classList.toggle("display-none");
    clickProducts();
  } else {
    console.log("Не удалось получить данные с сервера, код ошибки: " + response.status);
  }
};

let showProduct = function(item) {
  let itemMenuDiv = document.createElement("div");
  itemMenuDiv.classList = "item-menu";

  let itemMenuTitleDiv = document.createElement("div");
  itemMenuTitleDiv.classList = "item-menu-title";
  itemMenuTitleDiv.setAttribute('data-id', item.id)
  itemMenuTitleDiv.innerHTML = item.name.slice(0, 5);

  let itemMenuImg = document.createElement("img");
  itemMenuImg.src = "/images/imageFood.jpg";

  let itemMenuPriceDiv = document.createElement("div");
  itemMenuPriceDiv.classList = "item-menu-price";
  itemMenuPriceDiv.innerHTML = "Цена:" + item.price;

  let addBasketButton = document.createElement("button");
  addBasketButton.classList = "add-basket";
  addBasketButton.innerHTML = "Добавить";

  itemMenuDiv.append(itemMenuTitleDiv, itemMenuImg, itemMenuPriceDiv, addBasketButton);
  document.getElementById("menu").append(itemMenuDiv);
}

let pay = function() {
  let payButton = document.getElementById("pay");
  payButton.addEventListener("click", function() {
    if (productIds.length === 0) {
      return alert("Выберите пожалуйста товары для покупки");
    }

    let containerMenu = document.querySelectorAll(".container-menu"),
      containerPay = document.querySelectorAll(".container-pay");

    containerMenu[0].classList = "display-none";
    containerPay[0].classList.toggle("display-none");
  });
}

let finishOrder = function() {
  let finishOrder = document.getElementById("formPay");
  finishOrder.addEventListener("submit", function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    console.log(formData.get("name"));

    let formDataRequest = new FormData();
    formDataRequest.append('name', formData.get("name"));
    formDataRequest.append('sername', formData.get("sername"));
    formDataRequest.append('phone', formData.get("phone"));
    formDataRequest.append('email', formData.get("email"));
    formDataRequest.append('address', formData.get("address"));
    formDataRequest.append('item_ids', productIds.toString());
    formDataRequest.append('sum', sumProduct);
    if (telegram.initDataUnsafe.user) {
      formDataRequest.append('telegram_id', telegram.initDataUnsafe.user.id);
    }

    console.log(formDataRequest);

    fetch("/telegram/pay",
      {
        body: formDataRequest,
        method: "post"
      });

    document.querySelectorAll(".container-pay")[0].classList.toggle("display-none");
    document.querySelectorAll(".container-successful")[0].classList.toggle("display-none");
  });
},

  backPage = function() {
    buttonBackPage = document.getElementById('backPage');
    buttonBackPage.addEventListener('click', elem => {
       if (page - 1 >= 0) {
         page--;
       }
      document.getElementById("menu").innerHTML = '';
      getProducts();
      console.log(page);
    });
  },

  nextPage = function() {
    buttonNextBackPage = document.getElementById('nextPage');
    buttonNextBackPage.addEventListener('click', elem => {
      page++;
      document.getElementById("menu").innerHTML = '';
      getProducts();
      console.log(page);
    });
  }
