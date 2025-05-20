 // Lấy ngày hiện tại và định dạng
 const currentDate = new Date();
 const currentDateString = currentDate.toLocaleDateString('en-GB'); // Định dạng dd/mm/yyyy

 // Cập nhật placeholder với khoảng thời gian
 document.getElementById('daterange').placeholder = `01/01/2022-${currentDateString}`;

 // Khởi tạo flatpickr
 flatpickr("#daterange", {
     mode: "range",  // Cho phép chọn một khoảng thời gian
     dateFormat: "Y-m-d",  // Định dạng ngày
     minDate: "2022-01-01",  // Giới hạn ngày bắt đầu là 1/1/2022
     maxDate: currentDate,  // Giới hạn ngày kết thúc là ngày hiện tại
     locale: {
         firstDayOfWeek: 1, // Đặt ngày đầu tuần là thứ 2
     },
     onClose: function(selectedDates, dateStr, instance) {
         if (selectedDates.length === 2) {
             instance.input.value = selectedDates[0].toLocaleDateString() + " - " + selectedDates[1].toLocaleDateString();
         }
     }
 });

 document.addEventListener("DOMContentLoaded", function () {
     const selectedTab = localStorage.getItem("selectedTab");
     const selectedSubTab = localStorage.getItem("selectedSubTab");

     // Bật tab cha
     if (selectedTab) {
         const tabToActivate = document.querySelector(`[href="${selectedTab}"]`);
         const tabContentToShow = document.querySelector(selectedTab);

         if (tabToActivate && tabContentToShow) {
             document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove('active'));
             document.querySelectorAll('.tab-pane').forEach(content => content.classList.remove('show', 'active'));

             tabToActivate.classList.add('active');
             tabContentToShow.classList.add('show', 'active');

             // Nếu là tab_seven2 → xử lý tab con
             if (selectedTab === "#tab_seven2") {
                 let subTabToActivate, subTabContentToShow;

                 if (selectedSubTab) {
                     subTabToActivate = document.querySelector(`[href="${selectedSubTab}"]`);
                     subTabContentToShow = document.querySelector(selectedSubTab);
                 } else {
                     // Không có localStorage → bật tab_third1
                     subTabToActivate = document.querySelector('#tab_seven2 .nav-link[href="#tab_third1"]');
                     subTabContentToShow = document.querySelector('#tab_third1');
                 }

                 if (subTabToActivate && subTabContentToShow) {
                     document.querySelectorAll('#tab_seven2 .nav-link').forEach(tab => tab.classList.remove('active'));
                     document.querySelectorAll('#tab_seven2 .tab-pane').forEach(content => content.classList.remove('show', 'active'));

                     subTabToActivate.classList.add('active');
                     subTabContentToShow.classList.add('show', 'active');
                 }
             }
         }
     }

     // Lưu tab cha
     document.querySelectorAll('.nav-link').forEach(link => {
         link.addEventListener('click', function () {
             const href = link.getAttribute('href');
             localStorage.setItem("selectedTab", href);

             if (href !== '#tab_seven2') {
                 localStorage.removeItem("selectedSubTab");
             } else {
                 // Khi click tab_seven2 thì bật luôn tab_third1 và nội dung
                 const subTabToActivate = document.querySelector('#tab_seven2 .nav-link[href="#tab_third1"]');
                 const subTabContentToShow = document.querySelector('#tab_third1');

                 if (subTabToActivate && subTabContentToShow) {
                     document.querySelectorAll('#tab_seven2 .nav-link').forEach(tab => tab.classList.remove('active'));
                     document.querySelectorAll('#tab_seven2 .tab-pane').forEach(content => content.classList.remove('show', 'active'));

                     subTabToActivate.classList.add('active');
                     subTabContentToShow.classList.add('show', 'active');
                 }
             }
         });
     });

     // Lưu tab con
     document.querySelectorAll('#tab_seven2 .nav-link').forEach(link => {
         link.addEventListener('click', function () {
             const href = this.getAttribute('href');
             localStorage.setItem("selectedSubTab", href);
             localStorage.setItem("selectedTab", "#tab_seven2");
         });
     });
 });