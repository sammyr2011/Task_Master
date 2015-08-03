package com.example.lappy.taskmasterphase1;

/**
 * Created by lappy on 7/27/15.
 */
public class Message implements Comparable<Message>{
    private int senderid;
    private int timestamp;
    private String message;
    private boolean read;
    private String username;
    private int taskid;


    private int messageid;

    Message(int senderid,String username,int timestamp,String message,boolean read)
    {
        this.senderid=senderid;
        this.timestamp=timestamp;
        this.message=message;
        this.read=read;
        this.username=username;
    }

    Message(int senderid,String username,int timestamp,String message)
    {
        this(senderid,username,timestamp,message,false);
    }

    Message()
    {
        senderid=-1;
        timestamp=-1;
        message="";
        read=false;
        username="";
        messageid=-1;
        taskid=-1;
    }

    public int getSenderid() {
        return senderid;
    }

    public void setSenderid(int senderid) {
        this.senderid = senderid;
    }

    public int getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(int timestamp) {
        this.timestamp = timestamp;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public boolean isRead() {
        return read;
    }

    public void setRead(boolean read) {
        this.read = read;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public int getMessageid() {
        return messageid;
    }

    public void setMessageid(int messageid) {
        this.messageid = messageid;
    }

    public int getTaskid() {
        return taskid;
    }

    public void setTaskid(int taskid) {
        this.taskid = taskid;
    }

    @Override
    public int compareTo(Message another) {
        return another.timestamp-this.timestamp;
    }
}
