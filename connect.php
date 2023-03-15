<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medical - Home</title>
  <link rel="stylesheet" href="css/style.min.css">
</head>

<body>
  <?php include('include/header.php'); ?>

  <main class="main">
    <div class="breadcrumbs">
      <div class="container">
        <ul class="breadcrumbs__list">
          <li class="breadcrumbs__item breadcrumbs__item--home">
            <a class="breadcrumbs__link breadcrumbs__link--home" href="#">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M15.4631 7.35748H2.52563V13.3931C2.52563 13.8457 2.61486 14.2938 2.78822 14.7118C2.96157 15.1298 3.21565 15.5096 3.53591 15.8293C3.85617 16.1491 4.23633 16.4025 4.65464 16.5752C5.07296 16.7479 5.52121 16.8363 5.97376 16.8356H6.83438V11.6662H11.1431V16.8356H12.0038C12.4563 16.8363 12.9046 16.7479 13.3229 16.5752C13.7412 16.4025 14.1214 16.1491 14.4416 15.8293C14.7619 15.5096 15.0159 15.1298 15.1893 14.7118C15.3627 14.2938 15.4519 13.8457 15.4519 13.3931L15.4631 7.35748Z"
                  stroke="#007BFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path
                  d="M1.125 7.35753L6.13687 2.34566C6.89634 1.58654 7.9262 1.1601 9 1.1601C10.0738 1.1601 11.1037 1.58654 11.8631 2.34566L16.875 7.35753H1.125Z"
                  stroke="#007BFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </a>
          </li>
          <li class="breadcrumbs__item">
            <span class="breadcrumbs__link">Регистрация и подключение</span>
          </li>
        </ul>
      </div>
    </div>

    <section class="connection">
      <div class="container">
        <div class="connection__wrapper">

          <form class="connection-registr">
            <h3 class="connection-registr__title connection__title">
              Зарегестрироваться:
            </h3>
            <p class="connection-registr__text connection__text">
              Подключение с электронной почтой
            </p>

            <input class="connection-registr__input" type="text" for="firstName" placeholder="Имя">
            <input class="connection-registr__input" type="text" for="lastName" placeholder="Фамилия">
            <input class="connection-registr__input" type="email" for="mail" placeholder="Почтовый адрес">
            <input class="connection-registr__input" type="password" for="password" placeholder="Пароль">

            <p class="connection-registr__regulations">
              Регистрируясь, вы соглашаетесь с тем, что учетная запись будет создана на порталах pincetas.lt и
              manodaktaras.lt. Вы подтверждаете, что вам исполнилось 16 лет и вы ознакомились с
              <a href="#">Политикой конфиденциальности</a> и использования файлов cookie UAB Digibitas, а также с
              Правилами и информацией о
              конфиденциальности UAB Mano Mano Daktaras.
            </p>
            <button type="button" class="connection-registr__btn white-red__btn">
              Зарeгестрироваться
            </button>
          </form>

          <div class="connection-connect">
            <div class="connection-connect__network">
              <h3 class="connection-connect__title connection__title">
                Подключиться через соц. сети:
              </h3>
              <p class="connection-connect__text connection__text">
                Регистрация автоматическая
              </p>
              <a class="connection-connect__facebook" href="#">Facebook</a>
              <a class="connection-connect__google" href="#">Google</a>
            </div>

            <form class="connection-connect__login">
              <h3 class="connection-connect__login-title connection__title">
                Войти с помощью электронной почты:
              </h3>
              <input class="connection-registr__input" type="email" for="mail" placeholder="Почтовый адрес">
              <input class="connection-registr__input" type="password" for="password" placeholder="Пароль">
              <div class="connection-connect__box">
                <button type="button" class="connection-connect__btn red__btn">
                  Log in
                </button>
                <a class="connection-connect__forgot" href="#popup">Забыли пароль?</a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('include/footer.php'); ?>

  <!-- <div class="popup" id="popup">
    <a class="popup__area" href="#header"></a>
    <div class="popup__body">
      <div class="popup__content">
        <a class="popup__close" href="#header">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M10.4713 10.0293L15.8851 4.61551C16.0071 4.49348 16.0071 4.29562 15.8851 4.17359C15.763 4.05156 15.5652 4.05156 15.4431 4.17359L10.0293 9.58734L4.61554 4.17355C4.49351 4.05152 4.29565 4.05152 4.17362 4.17355C4.05155 4.29559 4.05155 4.49344 4.17362 4.61547L9.58737 10.0293L4.17358 15.4431C4.05151 15.5652 4.05151 15.763 4.17358 15.885C4.2346 15.9461 4.31456 15.9766 4.39456 15.9766C4.47456 15.9766 4.55452 15.9461 4.61554 15.885L10.0293 10.4712L15.4431 15.885C15.5042 15.9461 15.5841 15.9766 15.6641 15.9766C15.7441 15.9766 15.8241 15.9461 15.8851 15.885C16.0071 15.763 16.0071 15.5652 15.8851 15.4431L10.4713 10.0293Z"
              fill="black" />
          </svg>
        </a>
        <div class="popup__title">
          Забыли пароль?
        </div>
        <div class="popup__text">
          Впишите свой емайл для востановления пароля
        </div>
        <input class="connection-registr__input" type="email" for="mail" placeholder="Почтовый адрес">
        <button class="connection-connect__btn red__btn">
          Выслать новий пароль
        </button>
      </div>
    </div>
  </div> -->



  <?php include('include/script.php'); ?>
</body>

</html>