<!DOCTYPE html>
<html>
<head>
    <title>{{trans('lang.maintenance_title')}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">  
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  
  </head>
<style>
*, ::after, ::before {
  box-sizing: border-box;
}
* {
  outline: none;
html {
  font-family: sans-serif;
  line-height: 1.15;
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%;
  -ms-overflow-style: scrollbar;
  -webkit-tap-highlight-color: transparent;
   position: relative;
  min-height: 100%;
  background: #ffffff;
}
body {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    color: #000000;
    background: #fff;
}    
 .maintenance_section{
   display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}
.maintenance_section .maintenance_img,.maintenance_content{
    width: 50%;
    justify-content: center;
    align-items: center;
}
.maintenance_section .maintenance_img{
  text-align: center;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  flex-grow: 1;
}
.maintenance_content{
  width: 100%;
  max-width: 50%;
  background: #fff4f0;
  min-height: 100vh;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  padding: 50px 10%;
}
.maintenance_section .maintenance_img img{
 max-width: 100%;
 height: 100%;
 object-fit: cover;
}
.maintenance_content h2{
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 30px;
}
.maintenance_content p{
    font-size: 18px;
    line-height: 1.5em;
    font-weight: 500;
} 
@media screen and (max-width: 767px) {  
    .maintenance_section .maintenance_img,.maintenance_content{
    width: 100%;
    max-width: 100%;
}
}
</style>
<body>
     <div class="maintenance_section">
      <div class="maintenance_img"> 
        <img src="{{ asset('img/maintenance.jpg') }}" alt="Maintenance Mode">
      </div>
     <div class="maintenance_content"> 
       <div class="maintenance_content-inner">  
        <h2 class="mb-3">{{trans('lang.we_will_back_soon')}}</h2>
        <p>{{trans('lang.maintenance_message')}}</p>
      </div>
      </div>
     </div>
</body>
</html>