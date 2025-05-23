<html>
  <head>
    <title>Student Registration</title>
  </head>
  <body>
    <h2>Register Student</h2>
    <form action="RegisterServlet" method="post">
      Name: <input type="text" name="name" required /><br /><br />
      Email: <input type="email" name="email" required /><br /><br />
      Department: <input type="text" name="department" required /><br /><br />
      City: <input type="text" name="city" required /><br /><br />
      <input type="submit" value="Register" />
    </form>

    <br />
    <a href="ViewServlet">View Students</a>
  </body>
</html>
