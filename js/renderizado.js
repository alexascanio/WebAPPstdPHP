function renderizado(){
    var imgs = document.querySelectorAll('#imagenCarta');

    for (i=0; i<imgs.length; i++)
    {
      if (imgs[i].width > 0)
      {
        // console.log(imgs[i]);
        // console.log(imgs[i].width);
        // console.log(imgs[i].offsetWidth);
        imgs[i].style.height = imgs[i].width+'px';
      }
    }
  }