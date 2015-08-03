package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.content.Context;
import android.media.Image;
import android.provider.CalendarContract;
import android.text.Layout;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import java.sql.Date;
import java.text.DecimalFormat;
import java.util.Calendar;
import java.util.List;

/**
 * Created by lappy on 7/16/15.
 */
public class TaskListAdapter extends ArrayAdapter<Task> {

    public TaskListAdapter(Context context, int resource) {
        super(context, resource);
    }

    public TaskListAdapter(Context context, int resource, List<Task> objects) {
        super(context, resource, objects);
    }

    public TaskListAdapter(Context context, List<Task> tasks){
        super(context, R.layout.task_in_list,tasks);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View v = convertView;

        if(v==null)
        {
            LayoutInflater layoutInflater;
            layoutInflater = LayoutInflater.from(getContext());
            v = layoutInflater.inflate(R.layout.task_in_list, null);
        }

        Task t = getItem(position);

        if(t!=null)
        {
            TextView title = (TextView)v.findViewById(R.id.textViewTItle);
            TextView curBid = (TextView)v.findViewById(R.id.textViewCurBid);
            TextView timeLeft = (TextView)v.findViewById(R.id.textViewTimeLeft);
            ImageView thumbnail = (ImageView)v.findViewById(R.id.imageViewThumbnail);

            if(title!=null)
            {
                title.setText(t.getTitle());
            }

            if(curBid != null)
            {
                curBid.setText("$"+new DecimalFormat("#.00").format(t.getCurBid()));
            }

            if(timeLeft != null)
            {
                timeLeft.setText(t.getTimeLeftString());
            }

            if(thumbnail != null && t.getThumbnail() != null)
            {
                thumbnail.setImageBitmap(t.getThumbnail());
            }
        }

        return v;
    }
}
