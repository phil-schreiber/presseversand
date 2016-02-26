

<div class="container">
	{{ content() }}
<h1>{{tr('loginTitle')}}</h1>

<div class="loginForm">
  <form action="{{ form.getAction() }}" method="POST">
   <label for="username">Email: </label>
    {{form.render('username')}}<br/>
    

    <label for="password">Password: </label>
    {{form.render('password')}}<br>
    

    {{form.render('login')}}
  </form>
</div>
</div>