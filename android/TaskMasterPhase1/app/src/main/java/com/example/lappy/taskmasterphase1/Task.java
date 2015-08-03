package com.example.lappy.taskmasterphase1;

import android.graphics.Bitmap;

import java.sql.Date;

/**
 * Created by lappy on 7/16/15.
 */
public class Task {
    private String title;
    private int curBid;
    private int endTime;
    //private String imgUrl;
    private int taskId;
    private String description;

    public Bitmap getThumbnail() {
        return thumbnail;
    }

    public void setThumbnail(Bitmap thumbnail) {
        this.thumbnail = thumbnail;
    }

    private Bitmap thumbnail;

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public int getCurBid() {
        return curBid;
    }

    public void setCurBid(int curBid) {
        this.curBid = curBid;
    }

    public int getEndTime() {
        return endTime;
    }

    public void setEndTime(int endTime) {
        this.endTime = endTime;
    }


    public int getTaskId() {
        return taskId;
    }

    public void setTaskId(int taskId) {
        this.taskId = taskId;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getTimeLeftString()
    {
        long timeBetween = this.getEndTime()-System.currentTimeMillis()/1000;
        if(timeBetween/(24*60*60) > 0)
        {
            return timeBetween/(24*60*60)+" days left";
        }
        else if(timeBetween/(60*60) > 0)
        {
            return timeBetween/(60*60)+" hours left";
        }
        else if(timeBetween/(60) > 0)
        {
            return (timeBetween/(60))+" minutes left";
        }
        else if(timeBetween > 0)
        {
            return timeBetween+" seconds left";
        }
        else
        {
            return "This listing has ended.";
        }
    }

    public boolean isActive()
    {
        long timeBetween = this.getEndTime()-System.currentTimeMillis()/1000;

        return timeBetween > 0;
    }

    Task(String title, int curBid, int endTime, int taskId)
    {
        this(title, curBid, endTime, taskId, "", null);
    }

    Task(String title, int curBid, int endTime, int taskId, String description, Bitmap thumb)
    {
        this.title = title;
        this.curBid = curBid;
        this.endTime = endTime;
        this.taskId = taskId;
        this.description=description;
        this.thumbnail = thumb;
    }

    Task()
    {
        this.title = "";
        this.curBid = -1;
        this.endTime = -1;
        this.taskId = -1;
        this.description = "";
    }
}
