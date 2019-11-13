<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Codeigniter boilerplate - @thedijje</title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url('public/front/css/bootstrap.min.css')?>" rel="stylesheet">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
      <a class="navbar-brand" href="<?php echo base_url()?>">CI Boilerplate</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="<?php echo base_url()?>">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="https://github.com/thedijje/ci_boilerplate">Learn more</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://twitter.com/@thedijje">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="mt-5"><i class="fa fa-fire"></i> Codeigniter 3</h1>
        <p class="lead">Learn more about boilerplate, installation and setup!</p>
        <p>
          <a href="https://github.com/thedijje/ci_boilerplate" target="_blank"><button class="btn btn-primary"><i class="fa fa-github-alt"></i> View on github</button></a>
        </p>
        
        <div class="text-center">

          <ul class="list-unstyled">
            <li>Edit default route from <code>Home</code></li>
            <li>Copy <code>.ENV_example</code> to <code>.ENV</code> and update value as required</li>
            <li>Application directory location at <code><?php echo env('APP_DIR')?></code>, you can change so in <code>.ENV</code> file</li>
            <li>Used frontend libraries are <code>Fontawesome 4</code>, <code>Bootstrap 4</code>, <code>jQuery</code></li>
          </ul>

          </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript -->
  <script src="<?php echo base_url('static/front/js/')?>jquery.slim.min.js"></script>
  <script src="<?php echo base_url('static/front/js/')?>bootstrap.bundle.min.js"></script>

</body>

</html>
