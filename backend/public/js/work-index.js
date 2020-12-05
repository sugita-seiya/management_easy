window.addEventListener('load', function () {
  var work_section = window.document.getElementById('work-section').textContent;
  var work_section_propaty = document.getElementById('work-section');
  var count = document.querySelectorAll('tr').length;


  for (var i = 0; i < count; i++ ) {
    var work_section = window.document.getElementById('work-section').textContent;
    if(work_section == "法定休日"){
      work_section_propaty.classList.add('work-sunday');
    }
  }

  // let table = document.getElementById('targetTable');
  // let cells = table.querySelectorAll('td');
  // cells.forEach( (cell) =>  console.log(cell.innerHTML));

  // const name = $('#work-targetTable').length;
  // console.log(name);
});