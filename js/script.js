function medicalhub_api() {
  this.apiPath = 'api/'
  this.data = {};

  this.setRequestData = function(data) {
    this.data = typeof(data) === 'object' ? JSON.stringify(data) : data;
  }

  this.request = function(method, path, success, error) {
    var accessToken = getCookie('access_token'),
      dict = {
        url: this.apiPath + path,
        data: this.data,
        method: method,
        success: success,
        error: error,
      }

    if (accessToken !== undefined) {
      $.ajaxSetup({ headers: { 'Authorization': accessToken } });
    }

    if (this.data !== null) {
      dict['dataType'] = 'json';
    }
    $.ajax(dict);
    this.data = {};
  }
}

var mh_api = new medicalhub_api();

function getUrlParameter(sParam) {
  var sPageURL = window.location.search.substring(1),
    sURLVariables = sPageURL.split('&'),
    sParameterName,
    i;

  for (i=0; i < sURLVariables.length; i++) {
    sParameterName = sURLVariables[i].split('=');

    if (sParameterName[0] === sParam) {
      return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
    }
  }
  return false;
};

function serialize(obj) {
  let str = [];
  for (let p in obj)
    if (obj.hasOwnProperty(p)) {
      str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
    }
  return str.join("&");
}

function getPageName() {
  return window.location.pathname.replace('/', '').replace('.php', '');
}

function getCookie(name) {
  const cookies = document.cookie.split(';');
  for (var i=0; i < cookies.length; i++) {
    var arr = cookies[i].split('=');
    if (arr[0] === undefined || arr[1] === undefined)
      continue;
    var key = arr[0].toString().replace(' ', ''),
      value = arr[1].toString().replace(' ', '');
    if (key == name) {
      return value;
    }
  }
}

function setCookie(name, value) {
  document.cookie = name + "=" + value;
}

function deleteCookie(name) {
  setCookie(name, ";expires=Thu, 01 Jan 1970 00:00:01 GMT")
}

function loadPage(pageName) {
  updateHeaderMenu();
  $('main').load(pageName + ' main > *', function () {
    if ($('#popup').length > 0)
      $('#popup').remove();
    window.history.pushState({}, document.title, pageName);
    $(window).unbind('resize');
    $(document).unbind('ready');
    $(document).ready(pageFunctions[getPageName()]);
    $('.breadcrumbs__link.breadcrumbs__link--home:last').click(function () {
      loadPage('/');
    });
  });
}

function isUserLoggedIn() {
  return getCookie('access_token') !== undefined;
}

function updateHeaderMenu() {
  var isLoggedIn = isUserLoggedIn();
  $('.header__menu.menu').css('display', isLoggedIn ? 'none' : 'flex');
  $('.header__menu.menu__connected').css('display', isLoggedIn ? 'flex' : 'none');
}

function pageFunctionIndex() {
  mh_api.request('get', 'directions/list', function(xhr) {
    var list = xhr;
    for (var l=0; l < list.length; l++) {
      var directions_list = $('<a></a>').addClass('directions__list'),
      directions_box = $('<div></div>').addClass('directions__box'),
      directions_img = $('<img></img>').addClass('directions__img'),
      directions_inner = $('<div></div>').addClass('directions__inner'),
      directions_subtitle = $('<span></span>').addClass('directions__subtitle'),
      directions_text = $('<span></span>').addClass('directions__text');
      directions_list.attr('href', '#');
      directions_list.attr('id', list[l].id);
      directions_img.attr('src', list[l].img_link);
      directions_subtitle.text(list[l].specialization_name);
      directions_text.text(list[l].doctors_count + ' doctors');
      directions_box.append(directions_img);
      directions_inner.append(directions_subtitle, directions_text);
      directions_list.append(directions_box, directions_inner);
      $('.directions__info').append(directions_list)
    }
    $('.directions__list').click(function(e) {
      loadPage('doctors?direction_id=' + e.currentTarget.id)
    });
  });
  mh_api.request('get', 'doctors/list', function(xhr) {
    var doctors = xhr.list;
    for (var d=0; d < doctors.length; d++) {
      var doctors_cart = $('<a></a>').addClass('doctors__cart'),
      doctors_box = $('<div></div>').addClass('doctors__box'),
      doctors_img = $('<img></img>').addClass('doctors__box-img'),
      doctors_info = $('<div></div>').addClass('doctors__info'),
      doctors_name = $('<h3></h3>').addClass('doctors__name'),
      doctors_position = $('<span></span>').addClass('doctors__position'),
      doctors_education = $('<span></span>').addClass('doctors__education'),
      doctors_work = $('<span></span>').addClass('doctors__work'),
      directions = '';
      for (var dd=0; dd < doctors[d].directions.length; dd++)
        directions += doctors[d].directions[dd] + '/'
      directions = directions.replace(/\/+$/, '');
      doctors_cart.attr('id', doctors[d].id);
      doctors_img.attr('src', 'images/doctors/doctor.svg');
      doctors_name.text(doctors[d].full_name);
      doctors_position.text(directions);
      doctors_education.text('Graduated from ' + doctors[d].graduated_of);
      doctors_work.text('Work in ' + doctors[d].work_in);
      doctors_box.append(doctors_img);
      doctors_info.append(doctors_name, doctors_position, doctors_education, doctors_work);
      doctors_cart.append(doctors_box, doctors_info);
      $('.doctors__wrap').append(doctors_cart);
    }
    $('.doctors__cart').click(function(e) {
      loadPage('doctor?id=' + e.currentTarget.id);
    });
  });
  $('.doctors__btn.btn-blue').click(function() {
    loadPage('doctors');
  });
  $('input.top__input').keypress(function(e) {
    if((e.keyCode ? e.keyCode : e.which) != 13)
      return;
    e.preventDefault();
    if (e.currentTarget.value == '')
      return;
    loadPage('doctors?search=' + e.currentTarget.value);
  });
}

var pageFunctions = {
  '': pageFunctionIndex,
  'index': pageFunctionIndex,
  'connect': function () {
    if (isUserLoggedIn()) {
      loadPage('profile');
      return;
    }
    function createPopUp() {
      var popUp = $('<div></div>').addClass('popup').attr('id', 'popup'),
        popUpArea = $('<a></a>').addClass('popup__area').attr('href', '#header'),
        popUpBody = $('<div></div>').addClass('popup__body'),
        popUpContent = $('<div></div>').addClass('popup__content'),
        popUpClose = $('<a></a>').addClass('popup__close').attr('href', '#header'),
        svg = $('<svg></svg>'),
        path = $('<path></>'),
        popUpTitle = $('<div></div>').addClass('popup__title').text('Забыли пароль?'),
        popUpText = $('<div></div>').addClass('popup__text').text('Впишите свой емайл для востановления пароля'),
        connectionInput = $('<input></input>').addClass('connection-registr__input'),
        connectionConnect = $('<button></button>').addClass('connection-connect__btn red__btn');
      svg.attr('width', '20').attr('height', '20').attr('viewBox', '0 0 20 20').attr('fill', 'none').attr('xmlns', 'http://www.w3.org/2000/svg');
      path.attr('d', 'M10.4713 10.0293L15.8851 4.61551C16.0071 4.49348 16.0071 4.29562 15.8851 4.17359C15.763 4.05156 15.5652 4.05156 15.4431 4.17359L10.0293 9.58734L4.61554 4.17355C4.49351 4.05152 4.29565 4.05152 4.17362 4.17355C4.05155 4.29559 4.05155 4.49344 4.17362 4.61547L9.58737 10.0293L4.17358 15.4431C4.05151 15.5652 4.05151 15.763 4.17358 15.885C4.2346 15.9461 4.31456 15.9766 4.39456 15.9766C4.47456 15.9766 4.55452 15.9461 4.61554 15.885L10.0293 10.4712L15.4431 15.885C15.5042 15.9461 15.5841 15.9766 15.6641 15.9766C15.7441 15.9766 15.8241 15.9461 15.8851 15.885C16.0071 15.763 16.0071 15.5652 15.8851 15.4431L10.4713 10.0293Z').attr('fill', 'black');
      connectionInput.attr('type', 'email').attr('for', 'email').attr('placeholder', 'Почтовый адрес');
      connectionConnect.text('Выслать новый пароль');
      svg.append(path);
      popUpClose.append(svg);
      popUpContent.append(popUpClose, popUpTitle, popUpText, connectionInput, connectionConnect);
      popUpBody.append(popUpContent);
      popUp.append(popUpArea, popUpBody);
      return popUp;
    }
    function createFormMessage(id) {
      var formMessage = $('<div></div>').attr('id', id);
      formMessage.css({
        'margin-bottom': '20px',
        'font-size': '20px',
        'display': 'none'
      });
      return formMessage;
    }
    function setFormMessage(id, message, color) {
      $('#' + id).css({
        'color': color,
        'display': 'block'
      }).text(message);
    }
    createPopUp().insertAfter($('footer'));
    createFormMessage('login-message').insertBefore($('.connection-connect__box'));
    createFormMessage('register-message').insertBefore($('.connection-registr__regulations'));

    //register
    $('.connection-registr__btn.white-red__btn').click(function() {
      var firstNameInput = $('input[for=firstName]')[0],
      lastNameInput = $('input[for=lastName]')[0],
      emailInput = $('input[for=mail]:first')[0],
      passwordInput = $('input[for=password]:first')[0],
      firstName = firstNameInput.value,
      lastName = lastNameInput.value,
      email = emailInput.value,
      password = passwordInput.value;
      mh_api.setRequestData({
        'first_name': firstName,
        'last_name': lastName,
        'email': email,
        'password': password
      });
      mh_api.request('post', 'user/register', function(xhr) {
        firstNameInput.value = '';
        lastNameInput.value = '';
        emailInput.value = '';
        passwordInput.value = '';
        var message = firstName + ', your account has been successfully registered';
        setFormMessage('register-message', message, 'green');
      }, function(xhr) {
        var errorMessages = {
          'empty_email': 'Email is empty',
          'invalid_email': 'Invalid email',
          'email_exist': 'Email already exists',
          'empty_password': 'Password is empty',
          'invalid_password': 'Invalid password',
          'empty_first_name': 'First name is empty',
          'invalid_first_name': 'Invalid first name',
          'empty_last_name': 'Last name is empty',
          'invalid_last_name': 'Invalid last name',
          'unknown_error': 'Unknown error'
        };
        if (errorMessages.hasOwnProperty(xhr.responseJSON.error)) {
          setFormMessage('register-message', errorMessages[xhr.responseJSON.error], 'red');
        } else {
          setFormMessage('register-message', 'Unknown error', 'red');
        }
      });
    });    

    //login
    $('.connection-connect__btn.red__btn:first').click(function() {
      // var emailInput = $('input[for=mail]:last'),
      // passwordInput = $('input[for=password]:last');
      var email = $('input[for=mail]:last')[0].value,
      password = $('input[for=password]:last')[0].value;
      mh_api.setRequestData({
        'email': email,
        'password': password
      });
      mh_api.request('post', 'user/login', function(xhr) {
        $('#login-message').css('display', 'none');
        setCookie('access_token', xhr.access_token);
        setCookie('expired', xhr.expired);
        loadPage('profile');
      }, function(xhr) {
        var errorMessages = {
          'user_not_found': 'Invalid email',
          'incorrect_password': 'Incorrect password'
        }
        if (errorMessages.hasOwnProperty(xhr.responseJSON.error)) {
          setFormMessage('login-message', errorMessages[xhr.responseJSON.error], 'red');
        } else {
          setFormMessage('login-message', 'Unknown error', 'red');
        }
      });
    });
  },
  "contacts": function () {
    $('input.top__input').keypress(function(e) {
      if((e.keyCode ? e.keyCode : e.which) != 13)
        return;
      e.preventDefault();
      if (e.currentTarget.value == '')
        return;
      loadPage('doctors?search=' + e.currentTarget.value);
    });
    // табы странички контактов
    $('.contacts-basic__item').on('click', function (e) {
      e.preventDefault();
      $('.contacts-basic__item').removeClass('contacts-basic__item--active');
      $(this).addClass('contacts-basic__item--active');

      $('.contacts-info__item').removeClass('contacts-info__item--active');
      $($(this).attr('href')).addClass('contacts-info__item--active');
    });
    var tab = getUrlParameter('tab');
    if (tab)
      $('.contacts-basic__item[href="#tab-' + tab + '"]').click();
  },
  'diseases': function () {
    function updateDiseasesList(letter) {
      var active_directory_item = $('.directory__disease-item.directory__disease-item--active');
      if (active_directory_item.length > 0)
        active_directory_item[0].classList.remove('directory__disease-item--active');
      $('#' + letter).parent().addClass('directory__disease-item--active');
      $('.directory__subtitle')[1].textContent = "Diseases by the letter " + letter;
      mh_api.request('get', 'diseases/directory?letter=' + letter, function(xhr) {
        $('.directory__name-item').remove();
        var diseases = xhr;
        for (var d=0; d < diseases.length; d++) {
          var item = $('<li></li>').addClass('directory__name-item'),
          link = $('<a></a>').addClass('directory__name-link');
          link.text(diseases[d].short_name)
          link.attr('id', diseases[d].id);
          link.attr('href', '#');
          item.append(link);
          $('.directory__name').append(item);
        }
        $('.directory__name-link').unbind('click');
        $('.directory__name-link').click(function(e) {
          loadPage('disease?id=' + e.target.id);
        });
      }, function() {

      });
    }
    mh_api.request('get', 'diseases/popular', function(xhr) {
      var diseases = xhr;
      for (var d=0; d < diseases.length; d++) {
        var often_item = $('<li></li>').addClass('directory__often-item'),
        often_link = $('<a></a>').addClass('directory__often-link');
        often_link.attr('href', '#').attr('id', diseases[d].id);
        often_link.text(diseases[d].short_name);
        often_item.append(often_link);
        $('.directory__often').append(often_item);
      }
      $('.directory__often-link').click(function(xhr) {
        loadPage('disease?id=' + xhr.currentTarget.id);
      });
    });
    var disease_links = $('a.directory__disease-link');
    for (var d=0; d < disease_links.length; d++)
      disease_links[d].id = disease_links[d].textContent[0];
    updateDiseasesList(disease_links[0].textContent[0]);
    $('.directory__disease-item').click(function(e) {
      e.preventDefault();
      updateDiseasesList(e.currentTarget.children[0].textContent[0])
    });
  },
  'disease': function () {
    var disease_id = getUrlParameter('id');
    if (disease_id === undefined)
      loadPage('diseases');
    mh_api.request('get', 'disease/' + disease_id, function(xhr) {
      $('.breadcrumbs__link:last').text(xhr.short_name.split('')[0].toUpperCase())
      var disease_box_upper = $('<div></div>').addClass('disease__box'),
      disease_title = $('<h3></h3>').addClass('disease__title'),
      disease_text = $(xhr.definition).addClass('disease__text'),
      disease_subtitle = $('<h4></h4>').addClass('disease__subtitle'),
      disease_inner = $('<ul></ul>').addClass('disease__inner'),
      disease_box_lower = $('<div></div>').addClass('disease__box');
      disease_title.text(xhr.full_name);
      disease_box_upper.append(disease_title, disease_text);
      disease_subtitle.text("Articles on the topic of " + xhr.full_name + ":");
      for (var a=0; a < xhr.articles.length; a++) {
        var disease_item = $('<li></li>').addClass('disease__item'),
        disease_link = $('<a></a>').addClass('disease__link'),
        content = $(xhr.articles[a].content);
        disease_link.attr('href', '#' + xhr.articles[a].id);
        disease_link.text(xhr.articles[a].header);
        content[0].id = xhr.articles[a].id;
        disease_item.append(disease_link);
        disease_inner.append(disease_item);
        disease_box_lower.append(content);
      }
      $('div.container:even:odd').append(disease_box_upper, disease_subtitle, disease_inner, disease_box_lower);
      // $('.disease__title').text(xhr.full_name);
      // $('.disease__text').append(xhr.definition)
    });
    $('a.breadcrumbs__link:last').click(function() {
      loadPage('diseases');
    });
  },
  'doctor': function () {
    // var mixer = mixitup('.dortor-profile__wrapper');
    var doctor_id = getUrlParameter('id');
    if (doctor_id === undefined || doctor_id == '') {
      loadPage('doctors');
      return;
    }
    $('.dortor-profile__grade.white-red__btn').click(function() {
      loadPage('grade?doctor_id=' + doctor_id);
    });
    mh_api.request('get', 'doctor/' + doctor_id, function(xhr) {
      function createRaiting(recommends, reviews, grades) {
        var raiting = $('<div></div>').addClass('dortor-profile__raiting doctors__raiting'),
        itemRecomended = $('<span></span>').addClass('doctors__item doctors__item-recomended'),
        itemReviews = $('<span></span>').addClass('doctors__item doctors__item-reviews'),
        itemGrade = $('<span></span>').addClass('doctors__item doctors__item-grade');
        itemRecomended.text(recommends + " Recommends");
        itemReviews.text(reviews + " Reviews");
        itemGrade.text(grades + " Grades");
        raiting.append(itemRecomended, itemReviews, itemGrade);
        return raiting;
      }
      function createSpeciality(number, numberDate) {
        var speciality = $('<div></div>').addClass('dortor-profile__speciality'),
        specialities = [
          ["Provision register number: ", number],
          ["Date provision register number: ", numberDate]
        ];
        for (var s=0; s < specialities.length; s++) {
          var specialityText = $('<span></span>').addClass('dortor-profile__speciality-text'),
          specialityNumber = $('<span></span>').addClass('dortor-profile__speciality-number');
          specialityText.text(specialities[s][0]);
          specialityNumber.text(specialities[s][1]);
          speciality.append(specialityText.append(specialityNumber));
        }
        return speciality;
      }
      function createEducation(text, value) {
        var education = $('<div></div>').addClass('dortor-profile__education'),
        educationText = $('<span></span>').addClass('dortor-profile__education-text'),
        educationSubtext = $('<span></span>').addClass('dortor-profile__education-subtext');
        educationText.text(text);
        educationSubtext.text(value);
        education.append(educationText, educationSubtext);
        return education;
      }
      function createWork(place, apcYear, apcNumber) {
        var work = $('<div></div>').addClass('dortor-profile__work'),
        workText = $('<span></span>').addClass('dortor-profile__work-text'),
        workInner = $('<div></div>').addClass('dortor-profile__work-inner'),
        wraps = [
          [" year: ", apcYear],
          [" number: ", apcNumber]
        ];
        workText.text(place);
        for (var w=0; w < wraps.length; w++) {
          var workWrap = $('<div></div>').addClass('dortor-profile__work-wrap'),
          workApc = $('<span></span>').addClass('dortor-profile__work-apc'),
          workYear = $('<span></span>').addClass('dortor-profile__work-year'),
          workInfo = $('<span></span>').addClass('dortor-profile__work-info' + (w + 1));
          workApc.text('apc');
          workYear.text(wraps[w][0]);
          workInfo.text(wraps[w][1]);
          workWrap.append(workApc, workYear, workInfo);
          workInner.append(workWrap);
        }
        return work.append(workText, workInner);
      }
      function createWrapMix(name, date, comment, isRecommended) {
        var wrapMix = $('<div></div>').addClass('dortor-profile__wrap mix'),
        wrapTop = $('<div></div>').addClass('dortor-profile__wrap-top'),
        wrapName = $('<span></span>').addClass('dortor-profile__wrap-name'),
        wrapTime = $('<span></span>').addClass('dortor-profile__wrap-time'),
        wrapReview = $('<p></p>').addClass('dortor-profile__wrap-review');
        wrapMix.addClass('category-' + (isRecommended ? 'a' : 'b'));
        wrapReview.addClass('dortor-profile__wrap-review--' + (isRecommended ? 'yes' : 'now'));
        wrapName.text(name);
        wrapTime.text(date);
        wrapReview.text(comment);
        wrapTop.append(wrapName, wrapTime);
        wrapMix.append(wrapTop, wrapReview);
        return wrapMix;
      }
      function gradeInfoResize(item, progress, bage, avg_score) {
        var percentage, itemWidth;
        avg_score -= 1;
        percentage = ((avg_score / 3.00) * 100);
        itemWidth = parseFloat(item.css('width'));
        progress.css('width', percentage + '%');
        bage.css('left', ((itemWidth / 3.00) * avg_score) + 'px' );
        bage.text((avg_score + 1).toString().replace('.', ','));
      }
      function createGradeInfo(question, avg_score, answer) {
        var info = $('<div></div>').addClass('dortor-profile__grade-info grade-info'),
        infoText = $('<p></p>').addClass('grade-info__text'),
        infoItem = $('<div></div>').addClass('grade-info__item'),
        infoProgress = $('<span></span>').addClass('grade-info__progress'),
        infoBage = $('<span></span>').addClass('grade-info__bage'),
        infoAnswer = $('<p></p>').addClass('grade-info__answer');
        infoText.text(question);
        infoBage.text(avg_score);
        infoAnswer.text(answer);
        infoItem.append(infoProgress, infoBage);
        info.append(infoText, infoItem, infoAnswer);
        $(window).resize(function() {
          gradeInfoResize(infoItem, infoProgress, infoBage, avg_score);
        });
        return info;
      }
      $('.dortor-profile__speciality').remove();
      $('.dortor-profile__education').remove();
      $('.dortor-profile__work').remove();
      $('.dortor-profile__grade-info.grade-info').remove();
      $('.dortor-profile__wrap.mix').remove();

      $('span.breadcrumbs__link').text(xhr.full_name);
      $('.doctors__name').text(xhr.full_name);
      var specializations = "";
      for (var d=0; d < xhr.directions.length; d++) 
        specializations += xhr.directions[d].specialization_name + '/';
      $('.doctors__position').text(specializations.replace(/\/+$/, ''));

      $('.dortor-profile__inner:first').append(createRaiting(
        xhr.recommends_percent,
        xhr.reviews_count,
        xhr.grades_count
      ));
      
      $('.dortor-profile__inner.dortor-profile__inner-license').append(
        createSpeciality(xhr.provisional_registration_number, xhr.date_of_provisional_registration)
      );
      $('.dortor-profile__inner.dortor-profile__inner-education').append(
        createEducation("Qualification: ", xhr.qualification),
        createEducation("Graduated from: ", xhr.graduated_of)
      );
      for (var a=0; a < xhr.apc.length; a++) {
        $('.dortor-profile__inner.dortor-profile__inner-work').append(
          createWork(xhr.apc[a].place_of_practise_principle_first, xhr.apc[a].apc_year, xhr.apc[a].apc_no)
        );
      }
      
      for (var g=0; g < xhr.grade_stats.length; g++) {
        $('.dortor-profile__grade-box').append(
          createGradeInfo(
            xhr.grade_stats[g].question_text, 
            xhr.grade_stats[g].avg_score, 
            xhr.grade_stats[g].answer_text
          )
        );
      }
      $(window).resize();
      var reviewsCount = xhr.reviews.length,
      positiveReviewsCount = 0,
      negativeReviewsCount = 0; 
      for (var r=0; r < xhr.reviews.length; r++) {
        var isRecommended = xhr.reviews[r].is_recommended == 1 ? true : false;
        isRecommended ? positiveReviewsCount++ : negativeReviewsCount++;
        $('.dortor-profile__wrapper').append(
          createWrapMix(xhr.reviews[r].first_name, xhr.reviews[r].date, xhr.reviews[r].comment, isRecommended)
        );
      }
      $('.dortor-profile__info-dtn[data-filter="all"]')[0].childNodes[2].textContent = ' ' + reviewsCount + ')';
      $('.dortor-profile__info-dtn[data-filter=".category-a"]')[0].childNodes[2].textContent = ' ' + positiveReviewsCount + ')';
      $('.dortor-profile__info-dtn[data-filter=".category-b"]')[0].childNodes[2].textContent = ' ' + negativeReviewsCount + ')';
    });

    $('input.top__input').keypress(function(e) {
      if((e.keyCode ? e.keyCode : e.which) != 13)
        return;
      e.preventDefault();
      if (e.currentTarget.value == '')
        return;
      loadPage('doctors?search=' + e.currentTarget.value);
    }); 
  },
  'doctors': function () {
    // select
    var x, i, j, l, ll, selElmnt, a, b, c;
    /* Look for any elements with the class "custom-select": */
    x = document.getElementsByClassName("custom-select");
    l = x.length;
    for (i = 0; i < l; i++) {
      selElmnt = x[i].getElementsByTagName("select")[0];
      ll = selElmnt.length;
      /* For each element, create a new DIV that will act as the selected item: */
      a = document.createElement("DIV");
      a.setAttribute("class", "select-selected");
      a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
      x[i].appendChild(a);
      /* For each element, create a new DIV that will contain the option list: */
      b = document.createElement("DIV");
      b.setAttribute("class", "select-items select-hide");
      for (j = 1; j < ll; j++) {
        /* For each option in the original select element,
        create a new DIV that will act as an option item: */
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function (e) {
          /* When an item is clicked, update the original select box,
          and the selected item: */
          var y, i, k, s, h, sl, yl;
          s = this.parentNode.parentNode.getElementsByTagName("select")[0];
          sl = s.length;
          h = this.parentNode.previousSibling;
          for (i = 0; i < sl; i++) {
            if (s.options[i].innerHTML == this.innerHTML) {
              s.selectedIndex = i;
              h.innerHTML = this.innerHTML;
              y = this.parentNode.getElementsByClassName("same-as-selected");
              yl = y.length;
              for (k = 0; k < yl; k++) {
                y[k].removeAttribute("class");
              }
              this.setAttribute("class", "same-as-selected");
              break;
            }
          }
          h.click();
        });
        b.appendChild(c);
      }
      x[i].appendChild(b);
      a.addEventListener("click", function (e) {
        /* When the select box is clicked, close any other select boxes,
        and open/close the current select box: */
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
      });
    }

    function closeAllSelect(elmnt) {
      /* A function that will close all select boxes in the document,
      except the current select box: */
      var x, y, i, xl, yl, arrNo = [];
      x = document.getElementsByClassName("select-items");
      y = document.getElementsByClassName("select-selected");
      xl = x.length;
      yl = y.length;
      for (i = 0; i < yl; i++) {
        if (elmnt == y[i]) {
          arrNo.push(i)
        } else {
          y[i].classList.remove("select-arrow-active");
        }
      }
      for (i = 0; i < xl; i++) {
        if (arrNo.indexOf(i)) {
          x[i].classList.add("select-hide");
        }
      }
    }

    /* If the user clicks anywhere outside the select box,
    then close all select boxes: */
    document.addEventListener("click", closeAllSelect);

    var pagesCount = 0,
    currentPage = 0;

    function updateDoctorsList(page) {
      var requestData = {},
      checkedDirections = $('.doctors-page__direction-input:checkbox:checked');
      requestData['page'] = page;
      if (checkedDirections.length > 0) {
        requestData['direction_ids'] = ''
        for (var d=0; d < checkedDirections.length; d++)
          requestData['direction_ids'] += checkedDirections[d].id + ",";
      }
      if ($('.same-as-selected').length > 0)
        requestData['sort_by'] = $('.same-as-selected').attr('id');
      if ($('input.top__input')[0].value !== '')
        requestData['search_by_full_name'] = $('input.top__input')[0].value;
      mh_api.setRequestData(serialize(requestData));
      mh_api.request('get', 'doctors/list', function(xhr) {
        currentPage = page;
        pagesCount = xhr.pages_count;
        $('.doctors-page__all').text('All doctors ' + xhr.doctors_count);
        var cards = $('.doctors__cart'), doctors = xhr.list;
        for (var c=0; c < cards.length; c++) { 
          if (doctors[c] === undefined) {
            cards[c].style['display'] = 'none';
            continue;
          }
          var directions = '';
          for (var d=0; d < doctors[c].directions.length; d++)
            directions += doctors[c].directions[d] + '/'
          directions = directions.replace(/\/+$/, '');
          cards[c].style['display'] = '';
          cards[c].id = doctors[c].id;
          cards[c].href = "doctor?id=" + doctors[c].id;
          cards[c].getElementsByClassName('doctors__name')[0].textContent = doctors[c].full_name;
          cards[c].getElementsByClassName('doctors__position')[0].textContent = directions;
          cards[c].getElementsByClassName('doctors__education')[0].textContent = "Graduated from " + doctors[c].graduated_of;
          cards[c].getElementsByClassName('doctors__work')[0].textContent = "Work in " + doctors[c].work_in;
        }
        $('.pagination__link.pagination__link--active').removeClass('pagination__link--active')
        page = parseInt(page);
        var paginationLinks = $('.pagination__link'),
        isHalf = (xhr.pages_count / 2) < page,
        illusionPage = page + 1;
        if (paginationLinks.length >= (xhr.pages_count + 1)) {
          for (var p=0; p < paginationLinks.length; p++) {
            if (p > xhr.pages_count) {
              paginationLinks[p].parentElement.style['display'] = 'none';
              continue;
            }
            paginationLinks[p].textContent = p + 1
            paginationLinks[p].id = p;
            if (page == p)
              paginationLinks[p].classList.add('pagination__link--active');
          }
          return;
        }

        $('.pagination__item').css('display', '');

        paginationLinks[0].textContent = isHalf ? 1 : illusionPage;
        paginationLinks[1].textContent = isHalf ? '...' : illusionPage + 1;
        paginationLinks[2].textContent = isHalf ? illusionPage - 1 : '...';
        paginationLinks[3].textContent = isHalf ? illusionPage : xhr.pages_count + 1;

        paginationLinks[0].id = isHalf ? 0 : page;
        paginationLinks[1].id = isHalf ? page - 2 : page + 1;
        paginationLinks[2].id = isHalf ? page - 1 : page + 2;
        paginationLinks[3].id = isHalf ? page : xhr.pages_count;
        $('.pagination__link:' + (isHalf ? 'last' : 'first')).addClass('pagination__link--active');
      });
    }

    var search = getUrlParameter('search'),
    selectChildren = $('.select-items.select-hide').children(),
    sort_by_ids = [
      'name_asc', 
      'name_desc', 
      'lastname_asc', 
      'lastname_desc', 
      'rate_up', 
      'rate_down'
    ];

    if (search)
      $('input.top__input')[0].value = search;

    for (var s=0; s < selectChildren.length; s++)
      selectChildren[s].id = sort_by_ids[s];
    
    selectChildren.click(function() {
      updateDoctorsList(0);
    });

    mh_api.request('get', 'directions/list', function(xhr) {
      var list = xhr, directionIdParameter = getUrlParameter('direction_id');
      for (var l=0; l < list.length; l++) {
        var direction_label = $('<label></label>').addClass('doctors-page__direction-label'),
        direction_input = $('<input></input>').addClass('doctors-page__direction-input'),
        direction_check = $('<span></span>').addClass('doctors-page__direction-check');
        direction_input.attr('type', 'checkbox');
        direction_input.attr('id', list[l].id);
        if (directionIdParameter == list[l].id)
          direction_input[0].checked = true;
        direction_label.append(direction_input, direction_check);
        direction_label.append(list[l].name + ' (' + list[l].doctors_count + ')');
        $('.doctors-page__direction-form').append(direction_label);
      }
      $('.doctors-page__direction-input:checkbox').click(function() {
        updateDoctorsList(0);
      });
      updateDoctorsList(currentPage);
    });

    $('input.top__input').keypress(function(e) {
      if((e.keyCode ? e.keyCode : e.which) == 13)
        e.preventDefault();
    }).keyup(function(e) {
      updateDoctorsList(0);
    })

    $('.doctors__cart').click(function(e) {
      e.preventDefault();
      loadPage('doctor?id=' + e.currentTarget.id);
    });

    $('.pagination__link').click(function(e) {
      updateDoctorsList(e.currentTarget.id);
    })

    $('.pagination__arrows').click(function(e) {
      var newPage = e.currentTarget.classList[1] == 'pagination__prev' ? currentPage - 1 : currentPage + 1;
      if (newPage < 0 || newPage > pagesCount)
        return;
      updateDoctorsList(newPage);
    });
  },
  'grade': function () {
    var doctor_id = getUrlParameter('doctor_id');
    if (doctor_id == '' || doctor_id == undefined) 
      loadPage('doctors');
    if (!isUserLoggedIn()) 
      loadPage('connect');
    function createGradeBoxInner(id, number, question, answers) {
      var boxInner = $('<div></div>').addClass('grade-box__inner'),
      boxTop = $('<div></div>').addClass('grade-box__top'),
      boxNumber = $('<span></span>').addClass('grade-box__number'),
      boxTitle = $('<h4></h4>').addClass('grade-box__title'),
      boxQuestionnaire = $('<form></form>').addClass('grade-box__questionnaire');
      boxNumber.text(number);
      boxTitle.text(question);
      boxQuestionnaire.attr('questionid', id);
      for (var a=0; a < answers.length; a++) {
        var boxLabel = $('<label></label>').addClass('grade-box__label'),
        boxInput = $('<input></input>').addClass('grade-box__input')
        boxCheckbox = $('<span></span>').addClass('grade-box__checkbox'),
        boxText = $('<span></span>').addClass('grade-box__text');
        boxLabel.attr('answerid', answers[a].id)
        boxText.text(answers[a].text);
        boxInput.attr('type', 'checkbox');
        boxLabel.append(boxInput, boxCheckbox, boxText);
        boxQuestionnaire.append(boxLabel);
      }
      boxTop.append(boxNumber, boxTitle);
      boxInner.append(boxTop, boxQuestionnaire);
      return boxInner;
    }
    mh_api.request('get', 'doctor/' + doctor_id, function(xhr) {
      $('.grade__name').text(xhr.full_name);
      mh_api.request('get', 'grade/questions', function(xhr) {
        $('.grade-box__inner').remove();
        for (var q=0; q < xhr.length; q++) {
          $('.grade-box__wrap').append(createGradeBoxInner(
            xhr[q].id, q+1 + '/' + xhr.length, xhr[q].text, xhr[q].answers
          ));
        }
        var questionAnswers = [];
        $('.grade-box__input').click(function(e) {
          var questionId = e.currentTarget.parentElement.parentElement.getAttribute('questionid'),
          answerId = e.currentTarget.parentElement.getAttribute('answerid'),
          gradeLabels = $('.grade-box__label'),
          isQuestionExist = false;
          for (var q=0; q < questionAnswers.length; q++) {
            if (questionAnswers[q].question_id != questionId) 
              continue;
            isQuestionExist = true;
            questionAnswers[q].answer_id = answerId;
          }
          if (!isQuestionExist)
            questionAnswers.push({
              'question_id': questionId,
              'answer_id': answerId
            });
          for (var g=0; g < gradeLabels.length; g++) {
            if (gradeLabels[g].parentElement.getAttribute('questionid') != questionId)
              continue;
            if (gradeLabels[g].getAttribute('answerid') == answerId)
              continue;
            gradeLabels[g].children[0].checked = false;
          }
        });
        $('.regulations__btn').click(function() {
          var gradeDoctor = {
            'id': doctor_id,
            'comment': $('#myTextarea')[0].value,
            'question_answers': questionAnswers
          };
          mh_api.setRequestData(gradeDoctor);
          mh_api.request('post', 'grade/doctor', function() {
            loadPage('doctor?id=' + doctor_id);
          }, function() {

          })
        });
        mh_api.request('get', 'grade/doctor?id=' + doctor_id, function(xhr) {
          for (var a=0; a < xhr.answer_ids.length; a++)
            $('.grade-box__label[answerid="' + xhr.answer_ids[a] + '"]').children()[0].click();
          $('#myTextarea')[0].value = xhr.comment;
        });
      });
    }, function() {
      loadPage('doctors');
    }); 
      
  },
  'profile': function () {
    if (!isUserLoggedIn()) {
      loadPage('connect');
      return;
    }
    // табы странички товара
    $('.profile__top-item').on('click', function (e) {
      e.preventDefault();
      $('.profile__top-item').removeClass('profile__top-item--active');
      $(this).addClass('profile__top-item--active');

      $('.profile__content-item').removeClass('profile__content-item--active');
      $($(this).attr('href')).addClass('profile__content-item--active');
    });
  }
};

// кнопка бургер меню
$('.menu__btn, .menu a').on('click', function () {
  $('.menu__list, .menu__btn').toggleClass('active');
});

$('.menu__link, .footer-top__link').click(function (e) {
  e.preventDefault();
  if (e.currentTarget.href == 'connect' && isUserLoggedIn()) {
    deleteCookie('access_token');
    deleteCookie('expired');
  }
  loadPage(e.currentTarget.href);
});

$('.breadcrumbs__link.breadcrumbs__link--home:last').click(function () {
  loadPage('/');
});

$(document).unbind('ready');
$(document).ready(pageFunctions[getPageName()]);
updateHeaderMenu();