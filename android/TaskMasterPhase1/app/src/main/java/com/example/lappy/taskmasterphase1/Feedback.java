package com.example.lappy.taskmasterphase1;

/**
 * Created by lappy on 7/24/15.
 */
public class Feedback implements Comparable<Feedback>{
    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public double getRating() {
        return rating;
    }

    public void setRating(double rating) {
        this.rating = rating;
    }

    public int getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(int timestamp) {
        this.timestamp = timestamp;
    }

    public int getTaskid() {
        return taskid;
    }

    public void setTaskid(int taskid) {
        this.taskid = taskid;
    }

    public String getTimeLeftString()
    {
        long timeBetween = System.currentTimeMillis()/1000-this.getTimestamp();
        if(timeBetween/(365*24*60*60)>0)
        {
            return timeBetween/(365*24*60*60)+" years ago";
        }
        if(timeBetween/(24*60*60) > 0)
        {
            return timeBetween/(24*60*60)+" days ago";
        }
        else if(timeBetween/(60*60) > 0)
        {
            return timeBetween/(60*60)+" hours ago";
        }
        else if(timeBetween/(60) > 0)
        {
            return (timeBetween/(60))+" minutes ago";
        }
        else if(timeBetween > 0)
        {
            return timeBetween+" seconds ago";
        }
        else
        {
            return "";
        }
    }

    private String content;
    private double rating;
    private int timestamp;



    private int taskid;

    @Override
    public int compareTo(Feedback another) {
        return this.timestamp - another.timestamp;
    }
}
