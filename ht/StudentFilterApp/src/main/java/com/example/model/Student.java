package com.example.model;

public class Student {
    private String name;
    private String email;
    private String department;
    private String city;

    public Student(String name, String email, String department, String city) {
        this.name = name;
        this.email = email;
        this.department = department;
        this.city = city;
    }

    public String getName() { return name; }
    public String getEmail() { return email; }
    public String getDepartment() { return department; }
    public String getCity() { return city; }
}
