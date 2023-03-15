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
            <span class="breadcrumbs__link">Справочник заболеваний</span>
          </li>
        </ul>
      </div>
    </div>

    <section class="directory">
      <div class="container">
        <h2 class="directory__title">
          Справочник заболеваний
        </h2>
        <div class="directory__wrap">

          <!-- Часто запрашиваемые  -->
          <h3 class="directory__subtitle">
            Часто запрашиваемые
          </h3>
          <ul class="directory__often"></ul>

          <!-- Болезни -->
          <h3 class="directory__subtitle">
          </h3>
          <ul class="directory__disease">
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">0-9</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">A</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">B</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">C</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">D</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">E</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">F</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">G</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">H</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">I</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">J</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">K</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">L</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">M</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">N</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">O</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">P</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">Q</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">R</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">S</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">T</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">U</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">V</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">W</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">X</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">Y</a>
            </li>
            <li class="directory__disease-item">
              <a class="directory__disease-link" href="#">Z</a>
            </li>
          </ul>

          <!-- Название болезни -->
          <ul class="directory__name"></ul>
          <!-- Конец Название болезни -->
        </div>
      </div>
    </section>
  </main>

  <?php include('include/footer.php'); ?>
  <?php include('include/script.php'); ?>
</body>

</html>