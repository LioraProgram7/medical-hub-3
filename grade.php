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
            <span class="breadcrumbs__link">Оцените врача</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="grade" style="background-image: url('images/pattern-red.jpg')">
      <div class="container">
        <div class="grade__content">
          <div class="grade__box doctors__box">
            <img class="grade__box-img" src="images/icons/smile.svg" alt="">
          </div>
          <div class="grade__info">
            <span class="grade__rate">
              Оцените врача
            </span>
            <h3 class="grade__name">
            </h3>
          </div>
        </div>
      </div>
    </div>

    <div class="grade__subtitle">
      <div class="container">
        <p class="grade__subtext">
          Вы можете изменить или удалить оценку и/или комментарий позже.
        </p>
      </div>
    </div>

    <section class="grade-box">
      <div class="container">

        <div class="grade-box__wrap">
          <!-- правила написания -->
          <div class="grade-box__inner regulations">
            <div class="grade-box__top regulations__top">
              <h4 class="grade-box__title regulations__title">
                Важно! Правила написания отрицательного отзыва:
              </h4>
              <p class="regulations__text regulations__text-read">
                СТРОГО ЗАПРЕЩАЕТСЯ использовать нецензурные слова (матерные слова), унижать человека, обвинять человека
                в преступлении (подкупать, лечить, убивать и т.д.)и распространять неуместную клевету.
              </p>
              <ul class="regulations__example">
                <p class="regulations__subtitle">
                  Вот несколько примеров запрещенных отзывов:
                </p>
                <li class="regulations__item regulations__item-read">
                  "...требовали от меня взятку..." - обвиняется в совершении преступления, которое входит в компетенцию
                  правоохранительных органов
                </li>
                <li class="regulations__item regulations__item-read">
                  "...он шулер и мясник..." - употребляются унижающие и оскорбительные слова
                </li>
                <li class="regulations__item regulations__item-read">
                  "...проклятый алкоголик, крадущий больничный спирт..." - клевета, оскорбление и унижение
                </li>
              </ul>
            </div>

            <div class="regulations__middle">
              <p class="regulations__text regulations__text-orange">
                НЕ РЕКОМЕНДУЕТСЯ писать в ответе отрицательные утверждения или факты, так как это может быть правдой, но
                в
                случае возникновения правового спора может потребоваться доказывание конкретных юридических и
                потраченных
                впустую времени.
              </p>
              <ul class="regulations__example">
                <p class="regulations__subtitle">
                  Вот несколько примеров нерекомендованных отзывов:
                </p>
                <li class="regulations__item regulations__item-orange">
                  "...прописали не те анализы и лечили меня плохими препаратами, так я чуть не умер..."
                <li class="regulations__item regulations__item-orange">
                  "... знакомая сказала, что врач не лечил ее должным образом..." истинность таких утверждений не
                  получится
                </li>
              </ul>
            </div>

            <div class="regulations__bottom">
              <p class="regulations__text regulations__text-green">
                ВЫ МОЖЕТЕ ПОДЕЛИТЬСЯ С ДРУГИМИ ПАЦИЕНТАМИ ВАШИМ ЛИЧНЫМ МНЕНИЕМ И ОПЫТОМ, КОТОРЫЙ МОЖЕТ БЫТЬ ДАЖЕ ОЧЕНЬ
                КРИТИЧЕСКИМ, НО ЧЕСТНЫМ И НЕДЕГРИТАНТНЫМ
              </p>
              <ul class="regulations__example">
                <p class="regulations__subtitle">
                  Вот несколько примеров нерекомендованных отзывов:
                </p>
                <li class="regulations__item regulations__item-green">
                  "...не рекомендую, т.к. по моему мнению грубо общался и не отвечал на волнующие вопросы..." - личное
                  критическое мнение и отзыв, основанный на личном опыте
                <li class="regulations__item regulations__item-green">
                  "...мой личный опыт во время посещения не был безболезненным..." - исходя из личных впечатлений и
                  факта фактического посещения и субъективного мнения
                <li class="regulations__item regulations__item-green">
                  «…возможно, был перегружен, поэтому я думаю, что на посещение было потрачено слишком мало времени…» —
                  высказывалось личное мнение и субъективная интерпретация предыдущего факта
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- textarea -->
        <div class="regulations__form">
          <textarea class="regulations__textarea" name="comment" id="myTextarea" cols="30" rows="10"
            placeholder="Напишите, пожалуйста, ваше мнение о посещении врача..."></textarea>
          <button class="regulations__btn" type="submit">
            Сохранить рейтинг
          </button>
        </div>
      </div>
    </section>


  </main>

  <?php include('include/footer.php'); ?>
  <?php include('include/script.php'); ?>
</body>

</html>