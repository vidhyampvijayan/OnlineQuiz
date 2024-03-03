<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Online Quiz - Login</title>
      <link rel="stylesheet" href="{{ asset('css/style.css') }}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
   </head>
   <body>
      <div class="container">
         <header>Online Quiz - Login</header>
         @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-field">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
               <label>Email </label>
               @error('email')
                <span class="error">{{ $message }}</span>
            @enderror

            </div>
            
            <div class="input-field">
            <input id="password" type="password" name="password" required>
               <label>Password</label>
            </div>
            <div class="button">
               <div class="inner"></div>
               <button type="submit">LOGIN</button>
            </div>
         </form>
       
       
         <div class="signup">
            Not a member? <a href="{{ route('register') }}">Signup now</a>
         </div>
      </div>
      <script>
         var input = document.querySelector('.pswrd');
         var show = document.querySelector('.show');
         show.addEventListener('click', active);
         function active(){
           if(input.type === "password"){
             input.type = "text";
             show.style.color = "#1DA1F2";
             show.textContent = "HIDE";
           }else{
             input.type = "password";
             show.textContent = "SHOW";
             show.style.color = "#111";
           }
         }
      </script>
   </body>
</html>