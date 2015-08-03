package com.example.lappy.taskmasterphase1;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.example.lappy.taskmasterphase1.Message;

import org.w3c.dom.Text;

import java.util.List;

/**
 * Created by lappy on 7/27/15.
 */
public class MessageListAdapter extends ArrayAdapter<Message>
{
    public MessageListAdapter(Context context, int resource)
    {
        super(context, resource);
    }

    public MessageListAdapter(Context context, int resource, List<Message> objects) {
        super(context, resource, objects);
    }

    public MessageListAdapter(Context context, List<Message> messages)
    {
        super(context, R.layout.message_in_list, messages);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View v = convertView;

        if(v==null)
        {
            LayoutInflater layoutInflater;
            layoutInflater = LayoutInflater.from(getContext());
            v = layoutInflater.inflate(R.layout.message_in_list,null);
        }

        Message m = getItem(position);

        if(m!=null)
        {
            ImageView read = (ImageView)v.findViewById(R.id.imageViewMessageRead);
            TextView username = (TextView)v.findViewById(R.id.textViewMessageUsername);
            TextView content = (TextView)v.findViewById(R.id.textViewMessageContent);

            if(read!=null)
            {
                if(m.isRead())
                {
                    read.setImageResource(android.R.drawable.star_big_off);
                }
                else
                {
                    read.setImageResource(android.R.drawable.star_big_on);
                }
                //read.invalidate();
            }

            if(username!=null)
            {
                username.setText(m.getUsername());
                if(m.getSenderid()==0)
                {
                    username.setTextColor(Color.RED);
                }
            }

            if(content!=null)
            {
                content.setText(m.getMessage());
            }
        }

        return v;
    }
}
