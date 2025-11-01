@extends('layouts.frontend') <!-- Use your main layout -->

@section('title', 'Executive Committe | Nightingales of Kuwait')

@section('content')

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <span class="loader"></span>
    </div>
</div>

<!--==============================
Breadcumb
============================== -->
<div class="breadcumb-wrapper" style="background-image: url({{ asset('nokw/assets/img/breadcumb/bannn.jpg') }})">
  <div class="container z-index-common">
    <div class="breadcumb-content">
      <h1 class="breadcumb-title">Former Committees</h1>
      <div class="breadcumb-menu-wrap">
        <ul class="breadcumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
          <li>Former Committees</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<section class="committee-section">
  <div class="committee-wrapper">

    <!-- LEFT SIDE - TABS -->
    <div class="committee-tabs">
      <button class="tab-button active" data-target="committee-2024-2025">2024 – 2025</button>
      <button class="tab-button" data-target="committee-2023-2024">2023 – 2024</button>
      <button class="tab-button" data-target="committee-2022-2023">2022 – 2023</button>
      <button class="tab-button" data-target="committee-2019-2022">2019 – 2022</button>
      <button class="tab-button" data-target="committee-2018-2019">2018 – 2019</button>
      <button class="tab-button" data-target="committee-2017-2018">2017 – 2018</button>
      <button class="tab-button" data-target="committee-2016-2017">2016 – 2017</button>
    </div>

    <!-- RIGHT SIDE - CONTENT -->
    <div class="committee-content">

      <!-- 2024 – 2025 -->
      <div id="committee-2024-2025" class="committee-container active">
        <h2 class="committee-title">Executive Committee 2024 – 2025</h2>
        <p class="committee-period">Office Bearers 2024 – 2025</p>
        <table class="committee-table">
          <tr><th>Position</th><th>Name</th></tr>
          <tr><td>Patron</td><td>Mrs. Pushpa Susan George</td></tr>
          <tr><td>President</td><td>Mr. Ciril B Mathew</td></tr>
          <tr><td>Vice President</td><td>Mrs. Sonia Thomas</td></tr>
          <tr><td>Secretary</td><td>Mrs. Tresa Abraham</td></tr>
          <tr><td>Joint Secretary</td><td>Mr. Sobin Thomas</td></tr>
          <tr><td>Treasurer</td><td>Mrs. Sumi John</td></tr>
          <tr><td>Joint Accounts</td><td>Mr. Denny Thomson</td></tr>
          <tr><td>Auditor</td><td>Mrs. Shyju Rajan</td></tr>
        </table>

        <table class="committee-table">
          <tr><th colspan="2">Program Committee</th></tr>
          <tr><td>Co-ordinator</td><td>Mrs. Soumya Abraham</td></tr>
          <tr><td>Members</td><td>Mrs. Teena Thankachan, Mr. Sarath Kumar, Ms. Bindhumol Subash</td></tr>
        </table>

        <table class="committee-table">
          <tr><th colspan="2">Membership Committee</th></tr>
          <tr><td>Co-ordinator 1</td><td>Mrs. Prabha Raveendran</td></tr>
          <tr><td>Members</td><td>Mr. Midhun Abraham</td></tr>
        </table>

        <table class="committee-table">
          <tr><th colspan="2">Public Relation & Media Committee</th></tr>
          <tr><td>Co-ordinator</td><td>Mr. Sijumon Thomas</td></tr>
          <tr><td>Members</td><td>Ms. Sheeja Thomas, Mr. Sudesh Sudhakar</td></tr>
        </table>

        <table class="committee-table">
          <tr><th colspan="2">Arts & Sports Committee</th></tr>
          <tr><td>Co-ordinator</td><td>Mrs. Shirin Varghis</td></tr>
          <tr><td>Members</td><td>Mr. Chinnappa Dhas, Mr. Nitheesh Narayanan, Mrs. Preetha Thomas</td></tr>
        </table>

        <table class="committee-table">
          <tr><th colspan="2">Social Welfare Committee</th></tr>
          <tr><td>Co-ordinator</td><td>Mr. Aby Chacko Thomas</td></tr>
          <tr><td>Members</td><td>Mr. Roy K Yohannan, Mrs. Seema Francis, Mrs. Bindhu Thankachan</td></tr>
        </table>
      </div>

      <!-- Other years will follow same model -->
     <div id="committee-2023-2024" class="committee-container">
  <h2 class="committee-title">Executive Committee 2023 – 2024</h2>
  <p class="committee-period">(23/06/2023 to 05/07/2024)</p>

  <!-- Office Bearers -->
  <table class="committee-table">
    <tr><th>Office Bearers</th><th>Name</th></tr>
    <tr><td>Patron</td><td>Mrs. Pushpa Susan George</td></tr>
    <tr><td>President</td><td>Mr. Ciril B Mathew</td></tr>
    <tr><td>Vice President</td><td>Mrs. Sonia Thomas</td></tr>
    <tr><td>Secretary</td><td>Mrs. Tresa Abraham</td></tr>
    <tr><td>Joint Secretary</td><td>Mrs. Sumi John</td></tr>
    <tr><td>Treasurer Finance</td><td>Mr. Aby Chacko Thomas</td></tr>
    <tr><td>Treasurer Accounts</td><td>Mr. Sobin Thomas</td></tr>
    <tr><td>Auditor</td><td>Mrs. Shirin Varghis</td></tr>
  </table>

  <!-- Advisory Board -->
  <table class="committee-table">
    <tr><th colspan="2">Advisory Board</th></tr>
    <tr><td>Mr. Roy Yohannan</td></tr>
    <tr><td>Mrs. Bindu Thankachan</td></tr>
    <tr><td>Mrs. Sheeja Thomas</td></tr>
    <tr><td>Mr. Nithesh Narayanan</td></tr>
    <tr><td>Mr. Sijumon Thomas</td></tr>
  </table>

  <!-- Arts Committee -->
  <table class="committee-table">
    <tr><th colspan="2">Arts Committee</th></tr>
    <tr><td>Mrs. Seema Francis</td></tr>
    <tr><td>Mrs. Sreerekha Sajesh</td></tr>
  </table>

  <!-- Sports Committee -->
  <table class="committee-table">
    <tr><th colspan="2">Sports Committee</th></tr>
    <tr><td>Mr. Chinnappa Dhas</td></tr>
    <tr><td>Mrs. Bindhumol Subhash</td></tr>
  </table>

  <!-- Public Relations Committee -->
  <table class="committee-table">
    <tr><th colspan="2">Public Relations Committee</th></tr>
    <tr><td>Mr. Sudesh Sudhakar</td></tr>
    <tr><td>Mrs. Soumya Abraham</td></tr>
  </table>

  <!-- Media Co-ordinators -->
  <table class="committee-table">
    <tr><th colspan="2">Media Co-ordinators</th></tr>
    <tr><td>Mrs. Prabha Raveendran</td></tr>
    <tr><td>Mr. Melwin Joseph</td></tr>
  </table>

  <!-- Program Co-ordinators -->
  <table class="committee-table">
    <tr><th colspan="2">Program Co-ordinators</th></tr>
    <tr><td>Mr. Midhun Abraham</td></tr>
    <tr><td>Mrs. Preetha Thomas</td></tr>
  </table>
</div>


      <div id="committee-2022-2023" class="committee-container">
  <h2 class="committee-title">Executive Committee 2022 – 2023</h2>
  <p class="committee-period">(14/05/2022 to 23/06/2023)</p>

  <!-- Office Bearers -->
  <table class="committee-table">
    <tr><th>Office Bearers</th><th>Name</th></tr>
    <tr><td>President</td><td>Mr. Ciril B Mathew</td></tr>
    <tr><td>Vice President</td><td>Mrs. Sumi John</td></tr>
    <tr><td>Secretary</td><td>Mr. Sudesh Sudhakar</td></tr>
    <tr><td>Joint Secretary</td><td>Mrs. Shirin Varghis</td></tr>
    <tr><td>Treasurer</td><td>Mrs. Prabha Raveendran</td></tr>
    <tr><td>Media Coordinator</td><td>Mrs. Sheeja Thomas</td></tr>
    <tr><td>Arts Club Secretary</td><td>Mrs. Tresa Abraham</td></tr>
  </table>

  <!-- Advisory Board -->
  <table class="committee-table">
    <tr><th colspan="2">Advisory Board</th></tr>
    <tr><td>Mrs. Bindu Thankachan</td></tr>
    <tr><td>Mrs. Soumya Abraham</td></tr>
    <tr><td>Mr. Nithesh Narayanan</td></tr>
    <tr><td>Mr. Sijumon Thomas</td></tr>
    <tr><td>Mr. Nibu Pappachan</td></tr>
    <tr><td>Mrs. Sreerekha Sajesh</td></tr>
    <tr><td>Mr. Abdul Sathar</td></tr>
  </table>
</div>


<div id="committee-2019-2022" class="committee-container">
  <h2 class="committee-title">Executive Committee 2019 – 2022</h2>
  <p class="committee-period">(26/11/2019 to 14/05/2022)</p>

  <!-- Office Bearers -->
  <table class="committee-table">
    <tr><th>Office Bearers</th><th>Name</th></tr>
    <tr><td>President</td><td>Mr. Nithesh Narayanan</td></tr>
    <tr><td>Vice President</td><td>Mr. Sebastian Thomas</td></tr>
    <tr><td>Secretary</td><td>Mr. Sijumon Thomas</td></tr>
    <tr><td>Joint Secretary</td><td>Mrs. Prabha Raveendran</td></tr>
    <tr><td>Treasurer</td><td>Mrs. Sreerekha Sajesh</td></tr>
    <tr><td>Media Coordinator</td><td>Mr. Nibu Pappachan</td></tr>
    <tr><td>Arts Club Secretary</td><td>Mrs. Soumya Abraham</td></tr>
  </table>

</div>


<div id="committee-2018-2019" class="committee-container">
  <h2 class="committee-title">Executive Committee 2018 – 2019</h2>
  <p class="committee-period">(27/10/2018 to 26/11/2019)</p>

  <!-- Office Bearers -->
  <table class="committee-table">
    <tr><th>Office Bearers</th><th>Name</th></tr>
    <tr><td>President</td><td>Mrs. Soumya Abraham</td></tr>
    <tr><td>Vice President</td><td>Mrs. Prabha Raveendran</td></tr>
    <tr><td>Secretary</td><td>Mr. Abdul Sathar</td></tr>
    <tr><td>Joint Secretary</td><td>Mr. Sudesh Sudhakar</td></tr>
    <tr><td>Treasurer</td><td>Mr. Nibu Pappachan</td></tr>
    <tr><td>Joint Treasurer</td><td>Mr. Shinu Mathew</td></tr>
    <tr><td>Arts Club Secretary</td><td>Mr. Sijumon Thomas</td></tr>
  </table>

</div>


<div id="committee-2017-2018" class="committee-container">
  <h2 class="committee-title">NOK Executive Committee 2017 – 2018</h2>
  <p class="committee-period">(04/08/2017 to 27/10/2018)</p>

  <!-- Office Bearers -->
  <table class="committee-table">
    <tr><th>Office Bearers</th><th>Name</th></tr>
    <tr><td>President</td><td>Mrs. Seena K Chacko</td></tr>
    <tr><td>Vice President</td><td>Mr. Mejit</td></tr>
    <tr><td>Secretary</td><td>Mr. Nibu Pappachan</td></tr>
    <tr><td>Treasurer</td><td>Mr. Nithesh Narayanan</td></tr>
    <tr><td>Joint Treasurer</td><td>Mr. Shinu Mathew</td></tr>
  </table>

</div>


<div id="committee-2016-2017" class="committee-container">
  <h2 class="committee-title">FINA Executive Committee 2016 – 2017</h2>
  <p class="committee-period">(10/11/2016 to 04/08/2017)</p>

  <!-- Office Bearers -->
  <table class="committee-table">
    <tr><th>Office Bearers</th><th>Name</th></tr>
    <tr><td>President</td><td>Mr. Sebastian Thomas</td></tr>
    <tr><td>Vice President</td><td>Mrs. Seena K Chacko</td></tr>
    <tr><td>Vice President</td><td>Mr. Mayjo</td></tr>
    <tr><td>Secretary</td><td>Mr. Mejit</td></tr>
    <tr><td>Joint Secretary</td><td>Mr. Abdul Sathar</td></tr>
    <tr><td>Joint Secretary</td><td>Mr. Denny</td></tr>
    <tr><td>Treasurer</td><td>Mrs. Annamma Chacko</td></tr>
    <tr><td>Joint Treasurer</td><td>Mr. Nithesh Narayanan</td></tr>
    <tr><td>General Convenor</td><td>Mr. Nibu Pappachan</td></tr>
    <tr><td>Program Coordinator</td><td>Mr. Sijumon Thomas</td></tr>
    <tr><td>PRO</td><td>Melvin</td></tr>
  </table>

</div>




     

    </div>
  </div>
</section>




<style>

/* Mobile Responsive Only */
@media (max-width: 768px) {
  .committee-wrapper {
    flex-direction: column;
  }

  .committee-tabs {
    flex-direction: row;
    flex-wrap: wrap;
    gap: 8px;
    min-width: auto;
    margin-bottom: 15px;
  }

  .tab-button {
    flex: 1 1 auto;
    text-align: center;
    padding: 8px 10px;
    font-size: 0.9rem;
  }

  .committee-content {
    width: 100%;
  }

  .committee-table {
    font-size: 0.85rem;
  }

  .committee-table th,
  .committee-table td {
    padding: 6px;
  }
}

@media (max-width: 480px) {
  .tab-button {
    padding: 6px 8px;
    font-size: 0.8rem;
  }

  .committee-title {
    font-size: 1.2rem;
  }

  .committee-period {
    font-size: 0.8rem;
  }

  .committee-table th,
  .committee-table td {
    font-size: 0.75rem;
    padding: 4px;
  }
}




  .committee-wrapper {
  display: flex;
  gap: 20px;
  align-items: flex-start;
}

.committee-tabs {
  display: flex;
  flex-direction: column;
  gap: 10px;
  min-width: 180px;
}

.tab-button {
  padding: 10px 15px;
  text-align: left;
  background: #f0f4ff;
  border: 1px solid #ddd;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.3s;
}

.tab-button:hover {
  background: #e3ebff;
}

.tab-button.active {
  background: #0E5AF2;
  color: white;
}

.committee-content {
  flex: 1;
}

.committee-container {
  display: none;
}

.committee-container.active {
  display: block;
}

</style>
<script>
document.querySelectorAll(".tab-button").forEach(button => {
  button.addEventListener("click", () => {
    // Remove active from all buttons
    document.querySelectorAll(".tab-button").forEach(btn => btn.classList.remove("active"));
    // Add active to clicked button
    button.classList.add("active");

    // Hide all committee containers
    document.querySelectorAll(".committee-container").forEach(container => container.classList.remove("active"));
    // Show target container
    document.getElementById(button.dataset.target).classList.add("active");
  });
});
</script>

   
    



<style>
    /* Section Styling */
.committee-section {
  padding: 40px 140px;
  background-color: #f9f9f9;
}

/* Mobile Responsive Padding for Committee Section */
@media (max-width: 768px) {
  .committee-section {
    padding: 30px 20px;
  }
}

@media (max-width: 480px) {
  .committee-section {
    padding: 20px 10px;
  }
}

.committee-container {
  width: 100%;
  margin: 0;
  font-family: Arial, sans-serif;
  text-align: left;
  padding:0px 120px;
}

/* Mobile Responsive for Committee Container */
@media (max-width: 768px) {
  .committee-container {
    padding: 0 20px;
  }
}

@media (max-width: 480px) {
  .committee-container {
    padding: 0 10px;
  }
}


/* Title + Period */
.committee-title {
  color: #0E5AF2;
  font-size: 26px;
  font-weight: bold;
  margin-bottom: 5px;
}

.committee-period {
  color: #555;
  margin-bottom: 25px;
}

/* Table Styling */
.committee-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 30px;
  text-align: left;
}

.committee-table th {
  background-color: #0E5AF2;
  color: #fff;
  padding: 10px;
  font-weight: 600;
}

.committee-table td {
  padding: 8px 10px;
  border-bottom: 1px solid #ddd;
  color: #333;
}

.committee-table tr:nth-child(even) td {
  background-color: #f2f6ff; /* light theme color highlight */
}

</style>




@endsection
