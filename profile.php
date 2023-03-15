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
            <span class="breadcrumbs__link">Персональный кабинет</span>
          </li>
        </ul>
      </div>
    </div>

    <section class="profile">
      <div class="container">
        <div class="profile__top">
          <a class="profile__top-item profile__top-item--active" href="#tab-1">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M14.6935 12.8475C14.3715 11.4277 13.6126 10.1445 12.5235 9.17834C11.9805 9.89944 11.2773 10.4845 10.4695 10.8874C9.66164 11.2903 8.77126 11.5 7.86854 11.5C6.96581 11.5 6.07543 11.2903 5.26761 10.8874C4.45979 10.4845 3.75661 9.89944 3.21353 9.17834C2.1245 10.1445 1.36562 11.4277 1.04353 12.8475C0.985325 13.1059 0.985491 13.3741 1.04402 13.6324C1.10255 13.8907 1.21797 14.1328 1.38187 14.3408C1.54603 14.5468 1.75457 14.713 1.99192 14.8272C2.22928 14.9413 2.48933 15.0004 2.7527 15H12.9844C13.2477 15.0004 13.5078 14.9413 13.7451 14.8272C13.9825 14.713 14.191 14.5468 14.3552 14.3408C14.5191 14.1328 14.6345 13.8907 14.6931 13.6324C14.7516 13.3741 14.7517 13.1059 14.6935 12.8475Z"
                fill="black" />
              <path
                d="M7.86854 10.3333C10.4459 10.3333 12.5352 8.244 12.5352 5.66667C12.5352 3.08934 10.4459 1 7.86854 1C5.29121 1 3.20187 3.08934 3.20187 5.66667C3.20187 8.244 5.29121 10.3333 7.86854 10.3333Z"
                fill="black" />
            </svg>
            <span class="profile__top-title">Счет</span>
          </a>

          <a class="profile__top-item" href="#tab-2">
            <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9.49999 7.96426H2.49999V8.92855H9.49999V7.96426Z" fill="black" />
              <path d="M5.99999 9.89282H2.49999V10.8571H5.99999V9.89282Z" fill="black" />
              <path d="M9.49999 6.03574H2.49999V7.00002H9.49999V6.03574Z" fill="black" />
              <path
                d="M9.5 2.17857V1.21429H6.5V0.25H5.5V1.21429H2.5V2.17857H0V13.75H12V2.17857H9.5ZM3.5 2.17857H8.5V3.14286H3.5V2.17857ZM11 12.7857H1V3.14286H2.5V4.10714H9.5V3.14286H11V12.7857Z"
                fill="black" />
            </svg>
            <span class="profile__top-title">Новостная рассылка</span>
          </a>

          <a class="profile__top-item" href="#tab-3">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M12 9C12 11.8 9.3775 14 6.25 14C5.76631 14.0041 5.28383 13.9512 4.8125 13.8425L2 15.25V12.3675C-0.75 9.73 0.455 5.5375 4.25 4.3125C7.97 3.105 12 5.5 12 9Z"
                fill="black" />
              <path
                d="M14 9.1175V12L11.61 10.805C13.405 6.8225 8.8975 2.805 4.2425 4.305C4.9525 2.25 7.15 0.75 9.75 0.75C14.75 0.75 17.335 5.9275 14 9.1175Z"
                fill="black" />
              <path
                d="M9.75 0.25C7.07 0.25 4.75 1.715 3.86 3.92C-2.14577e-06 5.3275 -1.295 9.6975 1.5 12.575V15.25C1.5 15.3826 1.55268 15.5098 1.64644 15.6036C1.74021 15.6973 1.86739 15.75 2 15.75C2.165 15.75 2 15.81 4.88 14.3675C7.6075 14.9025 10.5 13.8025 11.83 11.4725C13.925 12.5225 13.835 12.5 14 12.5C14.1326 12.5 14.2598 12.4473 14.3536 12.3536C14.4473 12.2598 14.5 12.1326 14.5 12V9.325C17.925 5.7975 15.1275 0.25 9.75 0.25ZM6.25 13.5C4.0375 13.5 5.835 12.7725 2.5 14.44C2.5 12.24 2.5525 12.205 2.345 12.005C-0.690002 9.1025 1.75 4.5 6.25 4.5C9.97 4.5 12.455 7.715 11.1525 10.6025C10.3825 12.335 8.4125 13.5 6.25 13.5ZM13.655 8.75C13.4475 8.95 13.5 8.955 13.5 11.185C12.1125 10.4925 12.25 10.565 12.25 10.535C13.555 6.63 9.71 2.875 5.15 3.585C6.06 2.175 7.82 1.25 9.75 1.25C14.305 1.25 16.665 5.8775 13.655 8.75Z"
                fill="white" />
              <path
                d="M3 9.75C3.41421 9.75 3.75 9.41421 3.75 9C3.75 8.58579 3.41421 8.25 3 8.25C2.58579 8.25 2.25 8.58579 2.25 9C2.25 9.41421 2.58579 9.75 3 9.75Z"
                fill="#E8F3FF" />
              <path
                d="M6.25 9.75C6.66421 9.75 7 9.41421 7 9C7 8.58579 6.66421 8.25 6.25 8.25C5.83579 8.25 5.5 8.58579 5.5 9C5.5 9.41421 5.83579 9.75 6.25 9.75Z"
                fill="#E8F3FF" />
              <path
                d="M9.5 9.75C9.91421 9.75 10.25 9.41421 10.25 9C10.25 8.58579 9.91421 8.25 9.5 8.25C9.08579 8.25 8.75 8.58579 8.75 9C8.75 9.41421 9.08579 9.75 9.5 9.75Z"
                fill="#E8F3FF" />
            </svg>
            <span class="profile__top-title">Обратная связь</span>
          </a>
        </div>

        <div class="profile__content">
          <div class="profile__content-item profile__content-item--active" id="tab-1">
            <form class="profile__content-register connection-registr">
              <h3 class="connection-registr__title connection__title">
                Настройки учетной записи посетителя:
              </h3>

              <input class="connection-registr__input" type="text" for="firstName" required placeholder="Имя">
              <input class="connection-registr__input" type="text" for="lastName" required placeholder="Фамилия">
              <input class="connection-registr__input" type="email" for="mail" required placeholder="Почтовый адрес">
              <input class="connection-registr__input" type="password" for="password" required placeholder="Пароль">

              <p class="connection-registr__regulations">
                Регистрируясь, вы соглашаетесь с тем, что учетная запись будет создана на порталах pincetas.lt и
                manodaktaras.lt. Вы подтверждаете, что вам исполнилось 16 лет и вы ознакомились с
                <a href="#">Политикой конфиденциальности</a> и использования файлов cookie UAB Digibitas, а также с
                Правилами и информацией о
                конфиденциальности UAB Mano Mano Daktaras.
              </p>
              <button class="connection-registr__btn white-red__btn">
                Сохранить изменения
              </button>
            </form>
          </div>


          <div class="profile__content-item" id="tab-2">
            <form class="profile__content-registr connection-registr">
              <h3 class="profile__content-title connection__title">
                Настройки подписки на рассылку:
              </h3>
              <p class="profile__content-text connection__text">
                Раз в месяц получайте актуальные АКЦИИ Pincetas.lt, медицинских учреждений, лечебных услуг и другие
                новости!
              </p>

              <p class="profile__content-subtext">
                Чтобы упростить задачу, мы заполнили форму с адресом электронной
                почты вашей учетной записи Pincetas.lt. по почте и городу по IP адресу:
              </p>

              <input class="connection-registr__input" type="email" for="mail" required placeholder="Почтовый адрес">
              <input class="connection-registr__input" type="text" for="city" required placeholder="Город">

              <h4 class="profile__content-subtitle connection__title">
                Правила рассылки:
              </h4>
              <ul class="profile__content-info">
                <li class="profile__content-infotext">
                  Ваши данные (электронная почта и город) UAB Digibitas обрабатывает только с целью отправки
                  информационного бюллетеня и обязуется не разглашать эти данные кому-либо еще;
                </li>
                <li class="profile__content-infotext">
                  Эл. почта используется для отправки, город - для выбора информации по конкретному городу;
                </li>
                <li class="profile__content-infotext">
                  Информационный бюллетень – сборник информации и/или развлекательной информации, который также может
                  включать в себя информацию и/или рекламу, предоставляемую третьими лицами;
                </li>
                <li class="profile__content-infotext">
                  Вы можете в любое время отказаться от подписки на информационный бюллетень и удалить обрабатываемые
                  данные.
                </li>
              </ul>
              <button class="profile__content-btn white-red__btn">
                Coглашаюсь с правилами и подписываюсь!
              </button>
            </form>
          </div>


          <div class="profile__content-item" id="tab-3">
            <div class="profile__content-wrapper">
              <h3 class="profile__content-sutitle connection__title">
                Отзывы, которые вы написали:
              </h3>

              <ul class="profile__content-list-goup">
                <li class="profile__content-box">
                  <div class="profile__content-inner">
                    <span class="profile__content-date">
                      22-06-09 20:26:11
                    </span>
                    <span class="profile__content-recomend profile__content-recomend--yes">
                      Я рекомендую!
                    </span>
                    <a class="profile__content-namedoctor" href="#">
                      Имя доктора
                    </a>
                  </div>
                  <div class="profile__content-review">
                    Отзыв пациетра
                  </div>
                </li>

                <li class="profile__content-box">
                  <div class="profile__content-inner">
                    <span class="profile__content-date">
                      22-06-09 20:26:11
                    </span>
                    <span class="profile__content-recomend profile__content-recomend--now">
                      Я не рекомендую!
                    </span>
                    <a class="profile__content-namedoctor" href="#">
                      Имя доктора
                    </a>
                  </div>
                  <div class="profile__content-review">
                    Отзыв пациетра
                  </div>
                </li>
              </ul>

            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('include/footer.php'); ?>
  <?php include('include/script.php'); ?>
</body>

</html>