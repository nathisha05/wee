package com.example.servlet;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.sql.*;

public class RegisterServlet extends HttpServlet {
    private static final String DB_URL = "jdbc:mysql://localhost:3306/studentdb";
    private static final String DB_USER = "root";
    private static final String DB_PASS = "";

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        String name = request.getParameter("name");
        String email = request.getParameter("email");
        String department = request.getParameter("department");
        String city = request.getParameter("city");

        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection con = DriverManager.getConnection(DB_URL, DB_USER, DB_PASS)) {
                String sql = "INSERT INTO students (name, email, department, city) VALUES (?, ?, ?, ?)";
                try (PreparedStatement ps = con.prepareStatement(sql)) {
                    ps.setString(1, name);
                    ps.setString(2, email);
                    ps.setString(3, department);
                    ps.setString(4, city);
                    ps.executeUpdate();
                }
            }
        } catch (Exception e) {
            throw new ServletException("DB error: " + e.getMessage(), e);
        }

        // Save name in session
        request.getSession().setAttribute("userName", name);

        // Redirect to ViewServlet
        response.sendRedirect("ViewServlet");
    }
}
