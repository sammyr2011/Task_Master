package com.example.lappy.taskmasterphase1;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.RatingBar;
import android.widget.TextView;

import org.w3c.dom.Text;

import java.util.List;

/**
 * Created by lappy on 7/24/15.
 */
public class FeedbackListAdapter extends ArrayAdapter<Feedback> {

    public FeedbackListAdapter(Context context, List<Feedback> objects) {
        super(context, R.layout.feedback_in_list, objects);
    }
    public FeedbackListAdapter(Context context, int resource, List<Feedback> objects) {
        super(context, resource, objects);
    }

    public FeedbackListAdapter(Context context, int resource) {
        super(context, resource);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View v = convertView;

        if(v==null)
        {
            LayoutInflater layoutInflater;
            layoutInflater = LayoutInflater.from(getContext());
            v = layoutInflater.inflate(R.layout.feedback_in_list, null);
        }

        Feedback f = getItem(position);

        //Fill in content
        ////Feedback content
        if(f!=null)
        {
            TextView content = (TextView)v.findViewById(R.id.textViewFeedbackContent);
            TextView timestamp = (TextView)v.findViewById(R.id.textViewFeedbackTimestamp);
            RatingBar rating = (RatingBar)v.findViewById(R.id.ratingBarFeedback);

            if(content!=null)
            {
                content.setText(f.getContent());
            }

            if(timestamp!=null)
            {
                //TODO: Make this shit work
                //timestamp.setText(f.getTimestamp());
                timestamp.setText(f.getTimeLeftString());
            }

            if(rating!=null)
            {
                rating.setRating((float)f.getRating());
            }
        }


        return v;
    }
}
