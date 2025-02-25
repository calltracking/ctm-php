<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Demo CTM Embed Web Application</title>

  <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <script src="https://<?php echo $ctmHost; ?>/ctm-phone-embed-1.0.js"></script>

  <style>
  ctm-phone-embed {
    height: 750px;
    min-height: 750px;
    max-height: 750px;
    width: 100%;
    display: block;
    margin: 0;
    transition: height 0.5s ;
    padding: 0;
    position: relative;
    z-index: 1;
  }
  .live-call {
    visibility: hidden;
    display: none;
  }
  .live-call.active {
    visibility: visible;
    display: block;
  }
  /* example of how to style the phone inpue control */
  ctm-phone-input.text,
  ctm-phone-input.text::part(tel) {
    padding: 7px;
    border: 1px solid var(--input-border-color);
    margin: 2px 0;
    border-radius: 0;
    line-height: 20px;
    height: 32px;
    appearance: none;
  }
  ctm-phone-input.text {
    margin: 0;
    padding: 0;
    display: block;
    border-radius: var(--border-radius);
    width:200px;
  }
  ctm-phone-input.text::part(tel) {
    padding:0;
    border: none;
    font-size: 13px;
  }
  ctm-phone-input.text::part(tel),
  ctm-phone-input.text::part(box),
  ctm-phone-input.text::part(country) {
    height: 30px;
    line-height: 30px;
    padding: 0;
    margin: 0;
  }
  ctm-phone-input.text::part(tel) {
    width:120px;
  }
  ctm-phone-input.text::part(box) {
    width:180px;
  }
  </style>

</head>
<body>
  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/" id="status">...</a>
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3" href="#"><span data-feather="log-out"></span>Sign out</a>
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="live-call">
          <div class="input-group mb-3">
            <ctm-phone-input class="form-control light text transfer-cold" value="US" picker="off"></ctm-phone-input>
            <button class="btn btn-outline-secondary transfer-to-number-button" type="button">Transfer</button>
          </div>
          <div class="input-group mb-3">
            <ctm-phone-input class="form-control light text add-number" value="US" picker="off"></ctm-phone-input>
            <button class="btn btn-outline-secondary add-number-button" type="button">Add #</button>
          </div>
        </div>
        <!-- in this example you must call setToken manually -->
        <ctm-phone-embed popout="/ctm-device" id="phone"></ctm-phone-embed>
        <!-- here we'll fetch the access token for you -->
        <!--<ctm-phone-embed popout="/ctm-device" access="/ctm-phone-access" id="phone"></ctm-phone-embed>-->
        <!-- here we'll inline the device page so there is no popout -->
        <!--<ctm-phone-embed auto="true" access="/ctm-phone-access" id="phone"></ctm-phone-embed>-->
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom live-call">
          <h4 class="h4">Agent: <span class='agent-name'></span></h4>   <strong class="text-success">Time On Call: <span class='time-on-call'></span></strong>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
              <button type="button" class="btn btn-outline-secondary hold-button" data-bs-toggle="modal" data-bs-target="#exampleModal">Hold</button>
              <button  type="button" class="btn btn-outline-secondary mute-button" data-bs-toggle="modal" data-bs-target="#exampleNotes"><span data-feather="mic-off"></span>Mute</button>
              <button  type="button" class="btn btn-outline-success end-call-button"><span data-feather="phone-off"></span> End Call</button>
            </div>

          </div>


        </div>

        <div class="container-fluid">
          <form>
            <div class="row g-3 align-items-center">
              <div class="col-11">
                <label>Phone Number</label>
                <ctm-phone-input class="form-control light start-call-number" value="US" picker="off"></ctm-phone-input>
              </div>
              <div class="col-1">
                <button class="form-control btn btn-primary start-call" type="button" style="padding: 16px;margin-top: 20px;">Call</button>
              </div>
            </div>
          </form>
        </div>

      </main>
    </div>
  </div>

  <script src="/app.js"></script>
</body>
</html>
