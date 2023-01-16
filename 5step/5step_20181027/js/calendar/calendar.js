  var dayNames = ['일', '월', '화', '수', '목', '금', '토'];
var textProperty = 'textContent' in document.createElement('div') ? 'textContent' : 'innerText';

function consoleTime() {
  window.console && typeof window.console.time === "function" && window.console.time.apply(window.console, arguments);
}

function consoleTimeEnd() {
  window.console && typeof window.console.timeEnd === "function" && window.console.timeEnd.apply(window.console, arguments);
}

function consoleLog() {
  window.console && typeof window.console.log === "function" && window.console.log.apply(window.console, arguments);
}


//$(document).ready(function() {
document.addEventListener("DOMContentLoaded", function(event) {
  consoleTime('calendarBuild');
  var calendar = {};
  var calendarContainer = document.getElementById('calendar');

  var monthsFragment = document.createDocumentFragment();
  var daysFragment = document.createDocumentFragment();
  var weeksFragment = document.createDocumentFragment();



  var day = document.createElement('td');
  
  var date = new Date(2017, 8);
  var currentDate;
  var currentMonth;
  var currentYear;
  var month = date.getMonth();
  var table = document.createElement('table');
  var monthContainer = document.createElement('tbody');
  table.className = 'month';
  var weekContainer = document.createElement('tr');
  var emptyDay = document.createElement('td');
  var dayDiv = document.createElement('div');
  emptyDay.className = 'empty';

  var endDate = new Date(2018, 9);
  var leftDay = 0;
  var daysPerRow = 7;
  var fillHashMap = true;
  var alwaysSameHead = true;
  var uniquethead;

  function makeHead() {
    var thead;
    if (alwaysSameHead && uniquethead) {
      thead = uniquethead.cloneNode(true);
    } else {
      thead = document.createElement('thead');
      var tr = document.createElement('tr');
      thead.appendChild(tr);
      var headTdsFragment = document.createDocumentFragment();
      for (var i = 0; i < daysPerRow; i++) {
        var td = document.createElement('td');
        var div = document.createElement('div');
        div[textProperty] = dayNames[(leftDay + i) % daysPerRow];
        td.appendChild(div);
        headTdsFragment.appendChild(td);
      }
      tr.appendChild(headTdsFragment);
      uniquethead = thead;
    }
    thead.className = 'days';
    table.appendChild(thead);
  }

  function startTable() {
    table = table.cloneNode(false);
    makeHead();
    monthContainer = monthContainer.cloneNode(false);
    table.appendChild(monthContainer);
  }

  function endTable() {
    appendLakingTds();
    weekContainer.appendChild(daysFragment);
    weeksFragment.appendChild(weekContainer);
    monthContainer.appendChild(weeksFragment);
    monthsFragment.appendChild(table);
    weekContainer = weekContainer.cloneNode(false);
  }

  function dateToKey(y, m, d) {
    return y + '-' + (m < 10 ? '0' : '') + m + '-' + (d < 10 ? '0' : '') + d;
  }

  function nextWeek() {
    weekContainer.appendChild(daysFragment);
    weeksFragment.appendChild(weekContainer);
    weekContainer = weekContainer.cloneNode(false);
  }

  function nextMonth() {
    endTable();
    startTable();
    prependLakingTds();
  }

  function prependLakingTds() {
    var startDay = date.getDay();
    while (startDay != leftDay) {
      startDay = (startDay + (daysPerRow - 1)) % daysPerRow;
      daysFragment.appendChild(emptyDay);
      emptyDay = emptyDay.cloneNode(false);
    }
  }

  function appendLakingTds() {
    var startDay = date.getDay();
    while (startDay != leftDay) {
      startDay = (startDay + 1) % daysPerRow;
      daysFragment.appendChild(emptyDay);
      emptyDay = emptyDay.cloneNode();
    }
  }

  startTable();
  prependLakingTds();

  while (date < endDate) {
    currentDate = date.getDate();
    currentYear = date.getFullYear();
    currentMonth = date.getMonth();

    day = day.cloneNode(false);
    dayDiv = dayDiv.cloneNode(false);
    dayDiv[textProperty] = currentDate;

    if (fillHashMap) calendar[dateToKey(currentYear, currentMonth + 1, currentDate)] = day;
    day.appendChild(dayDiv);
    if (leftDay == date.getDay()) nextWeek();
    if (currentMonth != month) nextMonth();
    month = currentMonth;
    daysFragment.appendChild(day);
    date.setDate(currentDate + 1);
  }
  endTable();

  calendarContainer.appendChild(monthsFragment);
  consoleTimeEnd('calendarBuild');

  consoleLog(calendar);

  function setClassOnRange(className, rangeStart, rangeEnd, orderedExceptions) {
    consoleLog('setClassOnRange', arguments);
    consoleTime('range');
    var classNameSuffix = ' ' + className,
      currentExceptionIndex = 0,
      currentException = orderedExceptions instanceof Array ? orderedExceptions[currentExceptionIndex] : void 0;
    for (var d in calendar) {
      d >= rangeStart && d < rangeEnd && (currentException != d ? true : (currentException = orderedExceptions[++currentExceptionIndex], false)) && (calendar[d].className += classNameSuffix);
    }
    consoleTimeEnd('range');
  }

  setClassOnRange('active', '2013-03-05', '2020-05-03', ['2013-03-28', '2013-03-30', '2013-03-31']);



});