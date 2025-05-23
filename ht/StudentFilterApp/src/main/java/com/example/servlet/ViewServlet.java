package com.example.servlet;

import com.example.model.Student;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.sql.*;
import java.util.*;

public class ViewServlet extends HttpServlet {
    private static final String DB_URL = "jdbc:mysql://localhost:3306/studentdb";
    private static final String DB_USER = "root";
    private static final String DB_PASS = "";

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        // Get parameters
        String name = request.getParameter("filterName");
        String dept = request.getParameter("filterDept");
        String city = request.getParameter("filterCity");

        // Save to session
        HttpSession session = request.getSession();
        session.setAttribute("filterName", name);
        session.setAttribute("filterDept", dept);
        session.setAttribute("filterCity", city);

        // Save to cookies (1-day expiry)
        Cookie nameCookie = new Cookie("filterName", name);
        Cookie deptCookie = new Cookie("filterDept", dept);
        Cookie cityCookie = new Cookie("filterCity", city);

        nameCookie.setMaxAge(24 * 60 * 60); // 1 day
        deptCookie.setMaxAge(24 * 60 * 60);
        cityCookie.setMaxAge(24 * 60 * 60);

        response.addCookie(nameCookie);
        response.addCookie(deptCookie);
        response.addCookie(cityCookie);

        doGet(request, response); // Continue to show results
    }

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        HttpSession session = request.getSession();

        // Try session first
        String nameFilter = (String) session.getAttribute("filterName");
        String deptFilter = (String) session.getAttribute("filterDept");
        String cityFilter = (String) session.getAttribute("filterCity");

        // If session empty, check cookies
        if ((nameFilter == null || nameFilter.isEmpty()) &&
            (deptFilter == null || deptFilter.isEmpty()) &&
            (cityFilter == null || cityFilter.isEmpty())) {

            Cookie[] cookies = request.getCookies();
            if (cookies != null) {
                for (Cookie c : cookies) {
                    switch (c.getName()) {
                        case "filterName":
                            nameFilter = c.getValue();
                            session.setAttribute("filterName", nameFilter);
                            break;
                        case "filterDept":
                            deptFilter = c.getValue();
                            session.setAttribute("filterDept", deptFilter);
                            break;
                        case "filterCity":
                            cityFilter = c.getValue();
                            session.setAttribute("filterCity", cityFilter);
                            break;
                    }
                }
            }
        }

        List<Student> students = new ArrayList<>();

        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection con = DriverManager.getConnection(DB_URL, DB_USER, DB_PASS)) {
                StringBuilder query = new StringBuilder("SELECT * FROM students WHERE 1=1");
                List<String> params = new ArrayList<>();

                if (nameFilter != null && !nameFilter.isEmpty()) {
                    query.append(" AND name LIKE ?");
                    params.add("%" + nameFilter + "%");
                }
                if (deptFilter != null && !deptFilter.isEmpty()) {
                    query.append(" AND department LIKE ?");
                    params.add("%" + deptFilter + "%");
                }
                if (cityFilter != null && !cityFilter.isEmpty()) {
                    query.append(" AND city LIKE ?");
                    params.add("%" + cityFilter + "%");
                }

                try (PreparedStatement ps = con.prepareStatement(query.toString())) {
                    for (int i = 0; i < params.size(); i++) {
                        ps.setString(i + 1, params.get(i));
                    }
                    try (ResultSet rs = ps.executeQuery()) {
                        while (rs.next()) {
                            students.add(new Student(
                                    rs.getString("name"),
                                    rs.getString("email"),
                                    rs.getString("department"),
                                    rs.getString("city")
                            ));
                        }
                    }
                }
            }
        } catch (Exception e) {
            throw new ServletException("DB error: " + e.getMessage(), e);
        }

        request.setAttribute("filteredList", students);
        request.getRequestDispatcher("view.jsp").forward(request, response);
    }
}
