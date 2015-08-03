package com.example.lappy.taskmasterphase1;

/**
 * Created by lappy on 7/21/15.
 */
public class User {
    private int ratingWeight;
    private String name;
    private int id;
    private String avatarUrl;
    private double rating;

    User(){
        this.name="";
        this.id=-1;
        this.avatarUrl="";
        this.rating=-1;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getAvatarUrl() {
        return avatarUrl;
    }

    public void setAvatarUrl(String avatarUrl) {
        this.avatarUrl = avatarUrl;
    }

    public double getRating() {
        return rating;
    }

    public void setRating(double rating) {
        this.rating = rating;
    }

    public int getRatingWeight() {
        return ratingWeight;
    }

    public void setRatingWeight(int ratingWeight) {
        this.ratingWeight = ratingWeight;
    }
}
