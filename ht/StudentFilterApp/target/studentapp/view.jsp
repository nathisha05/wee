<%@ page import="java.util.List" %>
<%@ page import="com.example.model.Student" %>

<html>
<head>
    <title>Student View</title>
</head>
<body>
    <% String userName = (String) session.getAttribute("userName"); %>
    <% if (userName != null) { %>
        <p>Welcome, <b><%= userName %></b>!</p>
    <% } %>

    <h2>Filter Students</h2>
    <form method="post" action="ViewServlet">
        Name: <input type="text" name="filterName"
               value="<%= session.getAttribute("filterName") != null ? session.getAttribute("filterName") : "" %>" />
        Department: <input type="text" name="filterDept"
               value="<%= session.getAttribute("filterDept") != null ? session.getAttribute("filterDept") : "" %>" />
        City: <input type="text" name="filterCity"
               value="<%= session.getAttribute("filterCity") != null ? session.getAttribute("filterCity") : "" %>" />
        <input type="submit" value="Filter" />
    </form>

    <h2>Students List</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>City</th>
        </tr>
        <%
            List<Student> students = (List<Student>) request.getAttribute("filteredList");
            if (students != null && !students.isEmpty()) {
                for (Student s : students) {
        %>
        <tr>
            <td><%= s.getName() %></td>
            <td><%= s.getEmail() %></td>
            <td><%= s.getDepartment() %></td>
            <td><%= s.getCity() %></td>
        </tr>
        <%
                }
            } else {
        %>
        <tr><td colspan="4">No records found.</td></tr>
        <% } %>
    </table>
</body>
</html>
