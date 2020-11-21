window.addEventListener('load', function () {
  var work_start = window.document.getElementById('work-start_hours').textContent;
  var work_end = window.document.getElementById('work-end_hours').textContent;
  var workstart_btn = document.getElementById('workstart-btn');
  var workend_btn = document.getElementById('workend-btn');

  //出勤時間が空の場合は退勤ボタンを押せなくする
  if(work_start == ""){
    window.document.getElementById('workend-btn').disabled = true;
  }else{
    //出勤時間が入力済みの場合出勤ボタンを押せなくする
    if(work_start != ""){
      workstart_btn.classList.remove('work-start');
      workstart_btn.classList.add('color-gray');
      workend_btn.classList.add('work-start');
      window.document.getElementById('workstart-btn').disabled = true;
    }

    //出退勤時間が入力済みの場合は、両ボタンを押せなくする
    if(work_start != "" && work_end != ""){
      workend_btn.classList.add('color-gray');
      window.document.getElementById('workend-btn').disabled = true;
    }
  }
});