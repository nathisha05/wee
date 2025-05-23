package com.example;

import java.io.IOException;
import java.io.InputStream;
import java.io.PrintWriter;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;


public class XMLServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;
    
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/xml");
        
        InputStream xmlFile = getServletContext().getResourceAsStream("/WEB-INF/data.xml");
        
        if (xmlFile == null) {
            response.getWriter().write("<error>File not found</error>");
            return;
        }
        
        PrintWriter out = response.getWriter();
        int data;
        while ((data = xmlFile.read()) != -1) {
            out.print((char) data);
        }
        xmlFile.close();
        out.flush();
    }
}
