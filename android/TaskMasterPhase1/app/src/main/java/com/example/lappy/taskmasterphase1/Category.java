package com.example.lappy.taskmasterphase1;

/**
 * Created by lappy on 7/16/15.
 */
public class Category {
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    private int id;
    private String title;

    Category(String title, int id)
    {
        this.title=title;
        this.id=id;
    }

    @Override
    public String toString() {
        return getTitle();
    }
}
