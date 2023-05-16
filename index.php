<!DOCTYPE html>
<html lang="es">
<!-- Integrar Visual Studio code con GitHub -->
<!-- y crear ramas DEV, TEST -->
<head>

  <!-- Región SP -->
  <?php
    require 'include/class.php';

    $oSQL = new READ();

    #region SP  
    //Menu
    $menus = $oSQL->datos('sp_menu', ':date');

    //Sub Menu
    $submenus = $oSQL->datos('sp_submenu', ':date');

    //Category
    $categories = $oSQL->datos('sp_category', ':date');

    //Sub Category
    $subcategories = $oSQL->datos('sp_subcategory', ':date');

    //item
    $items = $oSQL->datos('sp_item', ':date');

    //allergen
    $allergens = $oSQL->datos('sp_allergen', '');

    //carousel
    $carousels = $oSQL->datos('sp_wc_carousel', '');

    //phone
    $phons = $oSQL->datos('sp_wc_phon', '');

    //link
    $links = $oSQL->datos('sp_wc_link', '');

    //company
    $wc_company = $oSQL->datos('sp_wc_company', '');

    //company
    $companies = $oSQL->datos('sp_wc_company', '');

    #endregion
  ?>

  <link rel="stylesheet" type="text/css" href="./css/home.css">
  <link rel="stylesheet" type="text/css" href="./css/home-logo.css">
  <link rel="stylesheet" type="text/css" href="./css/home-menu-burger.css"/>
  <link rel="stylesheet" type="text/css" href="./css/home-carousel.css"/>
  <link rel="stylesheet" type="text/css" href="./css/home-call.css"/>
  <link rel="stylesheet" type="text/css" href="./css/footer.css"/>
  <link rel="stylesheet" type="text/css" href="./css/menu.css"/>
  <link rel="stylesheet" type="text/css" href="./css/modal.css"/>
  <link rel="stylesheet" type="text/css" href="./css/modal-content.css"/>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Expires" content="Tue, 1 Jan 2023 00:00:00 GMT">

  <!-- Región Título etiqueta navegador -->
  <!-- Setear variable Moneda -->
  <?php
    $currency_symbol;
    foreach ($wc_company as $company)
    {
      echo '<title>'. strtoupper($company['rights']) .'</title>';

      $currency_symbol = $company['currency_symbol'];

    }
  ?>

</head>
<body>

<div class="home">
  <!-- Logo -->
  <div class="home-logo">
    <?php
    foreach ($companies as $company)
    {
      echo '
        <img src="./images/logo/'. $company['logo'] .'">
      ';
    }
    ?>
  </div>

  <!-- Botón menú Hamburguesa-->
  <div class="home-menu-burger">
    <a href="#menu"><img src="./images/iconos/menu_burger_original-removebg-preview.png"></a>
    <p>MENÚ</p>
  </div>

  <!-- menu -->
  <div id="menu" class="menumodalmask">
    <div class="menumodalbox menumovedown">
      <div class="div-modal-header">
        <div class="modal-close">
          <a href="#close" title="Close"><i class="fa-circle-xmark"></i></a>
        </div>
        <div class="modal-titulo">
          <p>MENÚ</p>
        </div>
      </div>
      <br>
      <br>

      <div class="menu">
        <ul class="acordeon">
          <?php
          #region Menú
            foreach ($menus as $menu)
            {
              $menu_id = $menu['id'];
              echo'
              <li>';
                if ($menu['submenu_count'] == 0)
                {
                  echo '
                  <a href="#'. base64_encode('modalmask'.$menu['id']) .'?" onclick="javascript:renderizado()">'.$menu['name'].'</a>';
                }
                else
                {
                  echo '
                  <p>'. $menu['name'] .'<i class="fa-chevron-down"></i></p>';

                  $submenu_filtered = [];
                  foreach ($submenus as $submenu)
                  {
                    if (stripos($submenu['menu_id'], $menu['id']) !== false)
                    {
                      array_push($submenu_filtered, $submenu);
                    }
                  }

                  echo '
                  <ul>
                    <li>';
                      foreach ($submenu_filtered as $submenu)
                      {
                        echo '
                        <a href="#'. base64_encode('modalmask'. $menu['id'] . $submenu['id']) . '?" onclick="javascript:renderizado()">'. $submenu['name'] .'</a>';
                      }
                      echo '
                    </li>
                  </ul>
                  ';
                }
                echo'
              <li>';
            }
            #endregion
          ?>
        </ul>
      </div>

    </div>
  </div>

  <!-- menu content -->
  <?php
    foreach ($menus as $menu)
    {
      //variable para mostrar el separador del menú > sub menú
      $separador = '';

      //llenar matriz $divIds para asociar a menú o sub menú
      $divIds = [];
      if ($menu['submenu_count'] == 0)
      {
        $divIds[] = ['id_m' => $menu['id'], 'name_m' => $menu['name'],
        'id_s' => '', 'name_s' => '', 'item_count_m' => $menu['item_count'],
        'item_count_s' => '',
        ];
      }
      else
      {
        $separador = ' > ';
        foreach ($submenus as $submenu)
        {
          if (stripos($submenu['menu_id'], $menu['id']) !== false)
          {
            $divIds[] = ['id_m' => $menu['id'], 'name_m' => $menu['name'],
            'id_s' => $submenu['id'], 'name_s' => $submenu['name'],
            'item_count_m' => '', 'item_count_s' => $submenu['item_count'],
            ];
          }
        }
      }

      //generar div para las distintas opciones del menú/sub menú
      foreach ($divIds as $divId)
      {
        echo '
        <div id="'. base64_encode('modalmask'. $divId['id_m'] . $divId['id_s']) .'?" class="modalmask">
          <div class="modalbox movedown">
            <div class="div-modal-header">
              <div class="modal-close">
                <a href="javascript:history.back()" title="Close"><i class="fa-arrow-left-xmark"></i></a>
              </div>
              <div class="modal-titulo">
                <p>' . trim($divId['name_m']) . $separador . trim($divId['name_s']) . '</p>
              </div>
            </div>
            <br>
            <br>';

            //Item por Menú
            if ($divId['item_count_m'] > 0)
            {
              foreach ($items as $item)
              {
                if ($item['menu_id'] == $divId['id_m'])
                {
                  //mostrar modo Carta
                  if ($item['view'] == 2)
                  {
                    echo '
                    <div class="div-formato-images">
                      <img src="images/' . $item['image'] . '">
                    </div>';
                  }
                  else //mostrar modo Imagen
                  {
                    echo '
                    <p class="name-price">
                      <span class="name">'. $item['name'] .'</span>
                      <span class="price">'. number_format($item['price'], 2, '.', '').' '. $currency_symbol .'</span>
                    </p>
                    <div class="carta-detalles">
                      <div class="carta-detalles-left">
                        <p>'. $item['description'] .'</p>                                  
                        <div class="carta-alergeno">';
                        //mostrar alergeno
                          foreach ($allergens as $allergen)
                          {
                            if ($allergen['item_id'] == $item['id'])
                            {
                              echo '<img src="images/iconos/' . $allergen['image'] . '">';
                            }
                          }
                        echo '</div>
                      </div>
                      <div class="carta-detalles-right">';
                        // Cadena de texto a evaluar 
                        $nombre_imagen = $item['image']; 
                        // Sólo se permiten gif, jpg ó jpeg, png y bmp
                        // sin sensibilidad a letras mayúsculas ni minúsculas 
                        $patron = "%\.(gif|jpe?g|png|bmp)$%i"; 
                        // visualización del resultado 
                        if (preg_match($patron, $nombre_imagen) == 1)
                        {
                          echo '<img src="./images/'. $item['image'] .'" id="imagenCarta">';
                        }
                      echo '</div>
                    </div>';
                  }
                }
              }
            }

            //Item por Sub Menú
            if ($divId['item_count_s'] > 0)
            {
              foreach ($items as $item)
              {
                if ($item['submenu_id'] == $divId['id_s'])
                {
                  //mostrar modo Carta
                  if ($item['view'] == 2)
                  {
                    echo '
                    <div class="div-formato-images">
                      <img src="images/' . $item['image'] . '">
                    </div>';
                  }
                  else //mostrar modo Imagen
                  {
                    echo '
                    <p class="name-price">
                      <span class="name">'. $item['name'] .'</span>
                      <span class="price">'. number_format($item['price'], 2, '.', '').' '. $currency_symbol .'</span>
                    </p>
                    <div class="carta-detalles">
                      <div class="carta-detalles-left">
                        <p>'. $item['description'] .'</p>                                  
                        <div class="carta-alergeno">';
                        //mostrar alergeno
                          foreach ($allergens as $allergen)
                          {
                            if ($allergen['item_id'] == $item['id'])
                            {
                              echo '<img src="images/iconos/' . $allergen['image'] . '">';
                            }
                          }
                        echo '</div>
                      </div>
                      <div class="carta-detalles-right">';
                        // Cadena de texto a evaluar 
                        $nombre_imagen = $item['image']; 
                        // Sólo se permiten gif, jpg ó jpeg, png y bmp
                        // sin sensibilidad a letras mayúsculas ni minúsculas 
                        $patron = "%\.(gif|jpe?g|png|bmp)$%i"; 
                        // visualización del resultado 
                        if (preg_match($patron, $nombre_imagen) == 1)
                        {
                          echo '<img src="./images/'. $item['image'] .'" id="imagenCarta">';
                        }
                      echo '</div>
                    </div>';
                  }
                }
              }
            }

            //para el manejo de la categoria asociada al menú o al sub menú
            if ($divId['id_s'] == '')
            {
              $divId_idx = 'id_m';
              $category_idx = 'menu_id';
            }
            else
            {
              $divId_idx = 'id_s';
              $category_idx = 'submenu_id';
            }

            $category_filtered = [];
            foreach ($categories as $category)
            {
              if (stripos((string)$divId[$divId_idx], (string)$category[$category_idx]) !== false)
              {
                array_push($category_filtered, $category);
              }
            }

            //para posteriormente manejar los items asociados a
            //la categoria o a la sub-categoria
            $categorysubcategories = [];
            foreach ($category_filtered as $category)
            {
              $categorysubcategories[] = [
                'id_c' => $category['id'], 'name_c' => $category['name'],
                'id_s' => null, 'name_s' => '',
              ];

              foreach ($subcategories as $subcategory)
              {
                if ($subcategory['category_id'] == $category['id'])
                {
                  $categorysubcategories[] = [
                    'id_c' => null, 'name_c' => '',
                    'id_s' => $subcategory['id'], 'name_s' => $subcategory['name'],
                  ];
                }
              }
            }

            //imprimir categoria, sub categoria e item
            foreach ($categorysubcategories as $categorysubcategory)
            {
              //mostrar Categoria
              echo '
              <div class="text-categoria-carta">'. $categorysubcategory['name_c'] .'</div>';

              //mostrar Sub categoria
              echo '
              <div class="text-subcategoria-carta">' . $categorysubcategory['name_s'] .'</div>';

              //para determinar si el item está asociado a
              //la categoria o a la sub-categoria
              if ($categorysubcategory['id_c'] !== null)
              {
                $itemParent = 'category_id';
                $idx = 'id_c';
              }
              else
              {
                $itemParent = 'subcategory_id';
                $idx = 'id_s';
              }

              //mostrar Item
              foreach ($items as $item)
              {
                if ($item[$itemParent] == $categorysubcategory[$idx])
                {
                  //mostrar modo Carta
                  if ($item['view'] == 2)
                  {
                    echo '
                    <div class="div-formato-images">
                      <img src="images/' . $item['image'] . '">
                    </div>';
                  }
                  else //mostrar modo Imagen
                  {
                    echo '
                    <p class="name-price">
                      <span class="name">'. $item['name'] .'</span>
                      <span class="price">'. number_format($item['price'], 2, '.', '').' '. $currency_symbol .'</span>
                    </p>
                    <div class="carta-detalles">
                      <div class="carta-detalles-left">
                        <p>'. $item['description'] .'</p>                                  
                        <div class="carta-alergeno">';
                        //mostrar alergeno
                          foreach ($allergens as $allergen)
                          {
                            if ($allergen['item_id'] == $item['id'])
                            {
                              echo '<img src="images/iconos/' . $allergen['image'] . '">';
                            }
                          }
                        echo '</div>
                      </div>
                      <div class="carta-detalles-right">';
                        // Cadena de texto a evaluar 
                        $nombre_imagen = $item['image']; 
                        // Sólo se permiten gif, jpg ó jpeg, png y bmp
                        // sin sensibilidad a letras mayúsculas ni minúsculas 
                        $patron = "%\.(gif|jpe?g|png|bmp)$%i"; 
                        // visualización del resultado 
                        if (preg_match($patron, $nombre_imagen) == 1)
                        {
                          echo '<img src="./images/'. $item['image'] .'" id="imagenCarta">';
                        }
                      echo '</div>
                    </div>';
                  }
                }
              }
            }
            echo '
          </div>
        </div>';
      }
    }
  ?>

  <!-- Carousel -->
  <div class="home-carousel">
    <div class="carousel-inner">
      <!-- Mostrar imagenes del carousel -->
      <?php
        $id = 0;
        foreach ($carousels as $carousel)
        {
          if ($carousel['is_cheked'] == 1)
          {
            $checked = 'checked="checked"';
          }
          else
          {
            $checked = '';
          }

          $id++;
          echo '
          <input class="carousel-open" type="radio" id="carousel-'. $id .'" name="carousel" aria-hidden="true" hidden=""  '. $checked .'>
          <div class="carousel-item">
              <img src="./images/carousel/'. $carousel['image'] .'">
          </div>
          ';
        }
      ?>
      
      <!-- controles prev y next para cambiar de imagen -->
      <?php
        $max = count($carousels);
          echo '<label for="carousel-'. $max .'" class="carousel-control prev control-1">‹</label>';
        for ($b = 2; $b <= count($carousels); $b++)
        {
          $c = $b - 1;
          echo '<label for="carousel-'. $c .'" class="carousel-control prev control-'. $b .'">‹</label>';
        }
      ?>
      <?php
        for ($b = 1; $b <= count($carousels); $b++)
        {
          $c = $b + 1;
          echo '<label for="carousel-'. $c .'" class="carousel-control next control-'. $b .'">›</label>';
        }
          echo '<label for="carousel-1" class="carousel-control next control-'. $max .'">›</label>';
      ?>
      
      <!-- control punto para cambiar de imagen -->
      <ol class="carousel-indicators">
        <?php
          $id = 0;
          foreach ($carousels as $carousel)
          {
            $id++;
            echo '
            <li>
              <label for="carousel-'. $id .'" class="carousel-bullet">•</label>
            </li>
            ';
          }
        ?>
      </ol>
    </div>
  </div>

  <!-- Call -->
  <div class="home-btn-llamar">

    <?php
      foreach ($phons as $phon)
      {
        echo '
        <div class="div-btn-llamar">
          <a class="btn-llamar" href="tel:'. str_replace(' ','',$phon['number']) .'"><i class="ico-llamar"></i>'. $phon['name'] .' '. $phon['number'] .'</a>
        </div>
        <br>
        ';
      }
    ?>

  </div>

</div>

<script src="./js/renderizado.js"></script>

</body>

<!-- footer -->
<footer>
  <!-- Link -->
  <div class="div-footer-links">  
    <?php
      foreach ($links as $link)
      {
        echo '<a href="'. $link['link'] .'"><img src="./images/iconos/'. $link['image'] .'"></a>
        ';
      }
    ?>
  </div>
  <!-- Rights -->
  <div class="div-footer-rights">
    <?php
      $date = date('Y');
      foreach ($wc_company as $company)
      {
        echo '<h4>© ' . $date . ' ' . $company['rights'] .' </h4>';
      }
    ?>
  </div>
</footer>

</html>