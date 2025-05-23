package com.example;

import jakarta.servlet.*;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.*;
import java.sql.*;


public class ExpenseServlet extends HttpServlet {

    private static final String DB_URL = "jdbc:mysql://localhost:3306/testdb";
    private static final String DB_USER = "root";
    private static final String DB_PASS = "";

    // Handles GET request (opens the page in browser)
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        displayPage(response, null, null, null, null, false);
    }

    // Handles POST request (adds expense)
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        String date = request.getParameter("date");
        String category = request.getParameter("category");
        String amount = request.getParameter("amount");
        String description = request.getParameter("description");

        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASS);

            String insertSql = "INSERT INTO expenses (date, category, amount, description) VALUES (?, ?, ?, ?)";
            PreparedStatement insertStmt = conn.prepareStatement(insertSql);
            insertStmt.setDate(1, java.sql.Date.valueOf(date));
            insertStmt.setString(2, category);
            insertStmt.setDouble(3, Double.parseDouble(amount));
            insertStmt.setString(4, description);
            insertStmt.executeUpdate();
            insertStmt.close();
            conn.close();

        } catch (Exception e) {
            e.printStackTrace();
        }

        displayPage(response, date, category, amount, description, true);
    }

    // Common function to display form and table
    private void displayPage(HttpServletResponse response, String date, String category, String amount,
                             String description, boolean added) throws IOException {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        out.println("<html><body>");
        if (added) {
            out.println("<h2>Expense Added Successfully!</h2>");
        }

        // Expense Form
        out.println("<form action='ExpenseServlet' method='post'>");
        out.println("Date: <input type='date' name='date' required><br>");
        out.println("Category: <input type='text' name='category' required><br>");
        out.println("Amount: <input type='number' step='0.01' name='amount' required><br>");
        out.println("Description: <input type='text' name='description'><br>");
        out.println("<input type='submit' value='Add Expense'>");
        out.println("</form><hr>");

        // Display all expenses
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASS);
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT * FROM expenses ORDER BY date DESC");

            out.println("<h3>All Expenses</h3>");
            out.println("<table border='1'>");
            out.println("<tr><th>ID</th><th>Date</th><th>Category</th><th>Amount</th><th>Description</th></tr>");

            while (rs.next()) {
                out.println("<tr>");
                out.println("<td>" + rs.getInt("id") + "</td>");
                out.println("<td>" + rs.getDate("date") + "</td>");
                out.println("<td>" + rs.getString("category") + "</td>");
                out.println("<td>" + rs.getDouble("amount") + "</td>");
                out.println("<td>" + rs.getString("description") + "</td>");
                out.println("</tr>");
            }

            out.println("</table>");
            rs.close();
            stmt.close();
            conn.close();

        } catch (Exception e) {
            out.println("<h3>Error loading expenses: " + e.getMessage() + "</h3>");
        }

        out.println("</body></html>");
    }
}
