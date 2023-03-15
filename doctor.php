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
            <span class="breadcrumbs__link"></span>
          </li>
        </ul>
      </div>
    </div>

    <section class="dortor-profile">
      <div class="dortor-profile__top">
        <div class="container">
          <form class="dortor-profile__form top__form">
            <span cla ss="top__search"></span>
            <input class="top__input" type="search" aria-label="Поиск: введите имя и фамилию доктора" required
              placeholder="Поиск: введите имя и фамилию доктора">
          </form>
        </div>
      </div>

      <div class="dortor-profile__bottom" style="background-image: url('images/pattern-red.jpg')">
        <div class="container">
          <div class="dortor-profile__bottom-info">
            <div class="dortor-profile__box doctors__box">
              <img class="doctors__box-img" src="images/doctors/doctor.svg" alt="doctor">
            </div>
            <div class="doctors__info">
              <h3 class="doctors__name"></h3>
              <span class="doctors__position"></span>
            </div>
            <button class="dortor-profile__grade white-red__btn">
              <span>Оцените врача</span>
              <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M11.6353 3.59034C11.6358 4.34888 11.3659 5.07908 10.8806 5.63209L6.16651 11.0007L1.45245 5.62234C0.969275 5.07178 0.699536 4.34551 0.697758 3.59034C0.697758 1.01621 3.49411 -0.30986 5.40088 1.22097L6.16651 1.83525L6.93213 1.22097C8.8462 -0.30986 11.6353 1.02206 11.6353 3.59034Z"
                  fill="#FDFDFD" />
                <path
                  d="M6.16667 11.3908C5.9388 11.3908 6.32526 11.7535 1.18646 5.88953C0.639907 5.26655 0.334967 4.44473 0.333332 3.59036C0.333332 0.690561 3.47604 -0.809066 5.61979 0.908972L6.16667 1.34774L6.71354 0.908972C8.85911 -0.809066 12 0.692511 12 3.59036C12.0001 4.44799 11.6951 5.27352 11.1469 5.89928C6.02083 11.7379 6.39271 11.3908 6.16667 11.3908ZM3.58359 0.963575C3.17944 0.951852 2.77884 1.04723 2.41748 1.24121C2.05612 1.4352 1.74521 1.72177 1.51244 2.07541C1.27967 2.42904 1.13226 2.83877 1.08333 3.26811C1.03441 3.69744 1.08549 4.13305 1.23203 4.53616C1.46719 5.16799 1.39062 4.98078 6.16667 10.4313C11.1141 4.78772 10.8698 5.15044 11.1013 4.53421C11.2435 4.14925 11.2975 3.73346 11.2588 3.32161C11.2202 2.90975 11.0899 2.51378 10.8789 2.16679C10.0786 0.860219 8.34323 0.577455 7.15104 1.533L6.38542 2.14729C5.82578 2.59581 5.31536 0.963575 3.58359 0.963575Z"
                  fill="#FA000E" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div class="dortor-profile__content">
        <div class="container">
          <div class="dortor-profile__inner">
            <h4 class="dortor-profile__text dortor-profile__text-raiting">
              Рейтинг
            </h4>
          </div>

          <div class="dortor-profile__inner dortor-profile__inner-license">
            <h4 class="dortor-profile__text dortor-profile__text-license">
              Лицензии
            </h4>
            <div class="dortor-profile__speciality">
              <span class="dortor-profile__speciality-text">
                Full register number:
                <span class="dortor-profile__speciality-number">
                  3456
                </span>
              </span>
              <span class="dortor-profile__speciality-text">
                Date of full register number:
                <span class="dortor-profile__speciality-number">
                  23.08.2006
                </span>
              </span>
            </div>
            <div class="dortor-profile__speciality">
              <span class="dortor-profile__speciality-text">
                Provision register number:
                <span class="dortor-profile__speciality-number">
                  323455
                </span>
              </span>
              <span class="dortor-profile__speciality-text">
                Date provision register number:
                <span class="dortor-profile__speciality-number">
                  23.08.2006
                </span>
              </span>
            </div>
          </div>

          <!-- ОБРАЗОВАНИЕ -->
          <div class="dortor-profile__inner dortor-profile__inner-education">
            <h4 class="dortor-profile__text dortor-profile__text-education">
              Education
            </h4>
            <div class="dortor-profile__education">
              <span class="dortor-profile__education-text">
                Qualification:
              </span>
              <span class="dortor-profile__education-subtext">
                bachelop of medicine bachelop of surgery
              </span>
            </div>
            <div class="dortor-profile__education">
              <span class="dortor-profile__education-text">
                Graduated from:
              </span>
              <span class="dortor-profile__education-subtext">
                univercity of melborne
              </span>
            </div>
          </div>

          <!-- конец-->

          <div class="dortor-profile__inner dortor-profile__inner-work">
            <h4 class="dortor-profile__text dortor-profile__text-work">
              Работает
            </h4>
            <div class="dortor-profile__work">
              <span class="dortor-profile__work-text">
                Название клиники
              </span>
              <div class="dortor-profile__work-inner">
                <div class="dortor-profile__work-wrap">
                  <span class="dortor-profile__work-apc">
                    apc </span>
                  <span class="dortor-profile__work-year">
                    year:
                  </span>
                  <span class="dortor-profile__work-info1">
                    lorem
                  </span>
                </div>
                <div class="dortor-profile__work-wrap">
                  <span class="dortor-profile__work-apc">
                    apc
                  </span>
                  <span class="dortor-profile__work-year">
                    number:
                  </span>
                  <span class="dortor-profile__work-info2">
                    lorem
                  </span>
                </div>
              </div>
            </div>
            <div class="dortor-profile__work">
              <span class="dortor-profile__work-text">
                Название клиники
              </span>
              <div class="dortor-profile__work-inner">
                <div class="dortor-profile__work-wrap">
                  <span class="dortor-profile__work-apc">
                    apc </span>
                  <span class="dortor-profile__work-year">
                    year:
                  </span>
                  <span class="dortor-profile__work-info1">
                    lorem
                  </span>
                </div>
                <div class="dortor-profile__work-wrap">
                  <span class="dortor-profile__work-apc">
                    apc
                  </span>
                  <span class="dortor-profile__work-year">
                    number:
                  </span>
                  <span class="dortor-profile__work-info2">
                    lorem
                  </span>
                </div>
              </div>
            </div>
            <div class="dortor-profile__work">
              <span class="dortor-profile__work-text">
                Название клиники
              </span>
              <div class="dortor-profile__work-inner">
                <div class="dortor-profile__work-wrap">
                  <span class="dortor-profile__work-apc">
                    apc </span>
                  <span class="dortor-profile__work-year">
                    year:
                  </span>
                  <span class="dortor-profile__work-info1">
                    lorem
                  </span>
                </div>
                <div class="dortor-profile__work-wrap">
                  <span class="dortor-profile__work-apc">
                    apc
                  </span>
                  <span class="dortor-profile__work-year">
                    number:
                  </span>
                  <span class="dortor-profile__work-info2">
                    lorem
                  </span>
                </div>
              </div>
            </div>


          </div>

          <div class="dortor-profile__inner dortor-profile__inner-grade">
            <h4 class="dortor-profile__text dortor-profile__text-grade">
              Подробная оценка
            </h4>
            <div class="dortor-profile__grade-box"></div>
          </div>

          <div class="dortor-profile__inner dortor-profile__inner-reviews">
            <h4 class="dortor-profile__text dortor-profile__text-reviews">
              Patient reviews
            </h4>
            <ul class="dortor-profile__info-top">
              <li class="dortor-profile__info-list">
                <button class="dortor-profile__info-dtn" type="button" data-filter="all">
                  All (<span></span> 0)
                </button>
              </li>
              <li class="dortor-profile__info-list">
                <button class="dortor-profile__info-dtn dortor-profile__info-dtn--like" type="button"
                  data-filter=".category-a">
                  recommend (<span class="hand like"></span> 0)
                </button>
              </li>
              <li class="dortor-profile__info-list">
                <button class="dortor-profile__info-dtn dortor-profile__info-dtn--dislike" type="button"
                  data-filter=".category-b">
                  do not recommend (<span class="hand dislike"></span> 0)
                </button>
              </li>
            </ul>

            <div class="dortor-profile__wrapper">
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