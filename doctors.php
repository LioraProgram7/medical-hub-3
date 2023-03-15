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
            <span class="breadcrumbs__link">Doctors</span>
          </li>
        </ul>
      </div>
    </div>

    <section class="doctors-page">
      <div class="doctors-page__top">
        <div class="container">
          <form class="doctors-page__form top__form">
            <span class="top__search"></span>
            <input class="top__input" type="search" aria-label="Поиск: введите имя и фамилию доктора" required
              placeholder="Поиск: введите имя и фамилию доктора">
          </form>
          <div class="doctors-page__text">
            <h2 class="doctors-page__title doctors__title">
              Doctors
            </h2>
            <span class="doctors-page__all"></span>
          </div>
        </div>
      </div>

      <div class="doctors-page__info">
        <div class="container">
          <div class="doctors-page__box">
            <div class="doctors-page__cards">

            <a class="doctors__cart" href="#">
                <div class="doctors__box">
                  <img class="doctors__box-img" src="images/doctors/doctor.svg" alt="doctor">
                </div>
                <div class="doctors__info">
                  <h3 class="doctors__name"></h3>
                  <span class="doctors__position"></span>
                  <span class="doctors__education"></span>
                  <span class="doctors__work"></span>
                </div>
                <div class="doctors__raiting">
                  <span class="doctors__item doctors__item-recomended"></span>
                  <span class="doctors__item doctors__item-reviews"></span>
                  <span class="doctors__item doctors__item-grade"></span>
                </div>
              </a>

              <a class="doctors__cart" href="#">
                <div class="doctors__box">
                  <img class="doctors__box-img" src="images/doctors/doctor.svg" alt="doctor">
                </div>
                <div class="doctors__info">
                  <h3 class="doctors__name"></h3>
                  <span class="doctors__position"></span>
                  <span class="doctors__education"></span>
                  <span class="doctors__work"></span>
                </div>
                <div class="doctors__raiting">
                  <span class="doctors__item doctors__item-recomended"></span>
                  <span class="doctors__item doctors__item-reviews"></span>
                  <span class="doctors__item doctors__item-grade"></span>
                </div>
              </a>

              <a class="doctors__cart" href="#">
                <div class="doctors__box">
                  <img class="doctors__box-img" src="images/doctors/doctor.svg" alt="doctor">
                </div>
                <div class="doctors__info">
                  <h3 class="doctors__name"></h3>
                  <span class="doctors__position"></span>
                  <span class="doctors__education"></span>
                  <span class="doctors__work"></span>
                </div>
                <div class="doctors__raiting">
                  <span class="doctors__item doctors__item-recomended"></span>
                  <span class="doctors__item doctors__item-reviews"></span>
                  <span class="doctors__item doctors__item-grade"></span>
                </div>
              </a>

              <a class="doctors__cart" href="#">
                <div class="doctors__box">
                  <img class="doctors__box-img" src="images/doctors/doctor.svg" alt="doctor">
                </div>
                <div class="doctors__info">
                  <h3 class="doctors__name"></h3>
                  <span class="doctors__position"></span>
                  <span class="doctors__education"></span>
                  <span class="doctors__work"></span>
                </div>
                <div class="doctors__raiting">
                  <span class="doctors__item doctors__item-recomended"></span>
                  <span class="doctors__item doctors__item-reviews"></span>
                  <span class="doctors__item doctors__item-grade"></span>
                </div>
              </a>

              <a class="doctors__cart" href="#">
                <div class="doctors__box">
                  <img class="doctors__box-img" src="images/doctors/doctor.svg" alt="doctor">
                </div>
                <div class="doctors__info">
                  <h3 class="doctors__name"></h3>
                  <span class="doctors__position"></span>
                  <span class="doctors__education"></span>
                  <span class="doctors__work"></span>
                </div>
                <div class="doctors__raiting">
                  <span class="doctors__item doctors__item-recomended"></span>
                  <span class="doctors__item doctors__item-reviews"></span>
                  <span class="doctors__item doctors__item-grade"></span>
                </div>
              </a>

              <a class="doctors__cart" href="#">
                <div class="doctors__box">
                  <img class="doctors__box-img" src="images/doctors/doctor.svg" alt="doctor">
                </div>
                <div class="doctors__info">
                  <h3 class="doctors__name"></h3>
                  <span class="doctors__position"></span>
                  <span class="doctors__education"></span>
                  <span class="doctors__work"></span>
                </div>
                <div class="doctors__raiting">
                  <span class="doctors__item doctors__item-recomended"></span>
                  <span class="doctors__item doctors__item-reviews"></span>
                  <span class="doctors__item doctors__item-grade"></span>
                </div>
              </a>

              <div class="pagination">
                <a class="pagination__arrows pagination__prev" href="#"></a>
                <ul class="pagination__list">
                  <li class="pagination__item">
                    <a class="pagination__link pagination__link--active" href="#"></a>
                  </li>
                  <li class="pagination__item">
                    <a class="pagination__link" href="#"></a>
                  </li>
                  <li class="pagination__item">
                    <span class="pagination__link" href="#"></span>
                  </li>
                  <li class="pagination__item">
                    <a class="pagination__link" href="#"></a>
                  </li>
                </ul>
                <a class="pagination__arrows pagination__next" href="#"></a>
              </div>
            </div>

            <div class="doctors-page__filters">
              <div class="custom-select" style="width:200px;">
                <select>
                  <option value="0">Сортировать</option>
                  <option value="1">Имя от А до Z</option>
                  <option value="2">Имя от Z до A</option>
                  <option value="3">Фамилия от А до Z</option>
                  <option value="4">Фамилия от Z до A</option>
                  <option value="5">Рейтинг от большего к меньшему</option>
                  <option value="6">Рейтинг от меньшего к большему</option>
                </select>
              </div>

              <div class="doctors-page__direction">
                <h4 class="doctors-page__direction-title">
                  Специализация:
                </h4>
                <form class="doctors-page__direction-form"></form>
                <a class="doctors-page__show-all" href="#">
                  Больше специальностей
                </a>
              </div>
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