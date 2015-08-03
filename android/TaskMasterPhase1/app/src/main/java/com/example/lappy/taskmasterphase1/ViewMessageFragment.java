package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.graphics.Color;
import android.location.GpsStatus;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.text.Html;
import android.text.Layout;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link ViewMessageFragment.OnViewMessageListener} interface
 * to handle interaction events.
 * Use the {@link ViewMessageFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class ViewMessageFragment extends Fragment {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    private static final String ARG_MESSAGEID = "messageid";
    private static final String ARG_SENDERID = "senderid";
    private static final String ARG_SENDERUSERNAME = "senderusername";
    private static final String ARG_CONTENT = "content";
    private static final String ARG_TIMESTAMP = "timestamp";
    private static final String ARG_READFLAG = "readflag";
    private static final String ARG_TASKID = "taskid";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    private int mMessageID;
    private int mSenderID;
    private String mSenderUsername;
    private String mContent;
    private int mTimestamp;
    private boolean mReadFlag;
    private int mTaskID;

    private ProgressBar mProgressBar;
    private LinearLayout mMainLayout;

    private Button mReplyButton;

    private OnViewMessageListener mListener;


    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param m Message
     * @return A new instance of fragment ViewMessageFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static ViewMessageFragment newInstance(Message m) {
        ViewMessageFragment fragment = new ViewMessageFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_MESSAGEID, m.getMessageid());
        args.putInt(ARG_SENDERID, m.getSenderid());
        args.putInt(ARG_TIMESTAMP, m.getTimestamp());
        args.putString(ARG_SENDERUSERNAME, m.getUsername());
        args.putString(ARG_CONTENT, m.getMessage());
        args.putBoolean(ARG_READFLAG, m.isRead());
        args.putInt(ARG_TASKID,m.getTaskid());
        fragment.setArguments(args);
        return fragment;
    }

    public ViewMessageFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mMessageID = getArguments().getInt(ARG_MESSAGEID);
            mSenderID = getArguments().getInt(ARG_SENDERID);
            mTimestamp = getArguments().getInt(ARG_TIMESTAMP);
            mSenderUsername = getArguments().getString(ARG_SENDERUSERNAME);
            mContent = getArguments().getString(ARG_CONTENT);
            mReadFlag = getArguments().getBoolean(ARG_READFLAG);
            mTaskID = getArguments().getInt(ARG_TASKID);
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.fragment_view_message, container, false);

        mMainLayout = (LinearLayout)v.findViewById(R.id.layoutViewMessageMain);

        TextView username = (TextView)v.findViewById(R.id.textViewViewMessageUsername);
        TextView timestamp = (TextView)v.findViewById(R.id.textViewViewMessageTimestamp);
        TextView content = (TextView)v.findViewById(R.id.textViewViewMessageContent);

        username.setText(mSenderUsername);
        SimpleDateFormat sdf = new SimpleDateFormat("EEE, d MMM yyyy hh:mm:ss a");
        timestamp.setText(sdf.format(new Date(mTimestamp*1000L)));
        content.setText(Html.fromHtml(mContent).toString());

        mReplyButton = (Button)v.findViewById(R.id.buttonViewMessageReply);
        if(mSenderID==0)
        {
            mReplyButton.setText("View Task");
            mReplyButton.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mListener.onViewTask(mTaskID);
                }
            });
            //TODO: System messages listener
        }
        else
        {
            mReplyButton.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mListener.onSendReply(mSenderID,mSenderUsername);
                }
            });
        }

        new MarkRead().execute();

        return v;
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnViewMessageListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnFragmentInteractionListener");
        }
    }

    @Override
    public void onDetach() {
        super.onDetach();
        mListener = null;
    }

    /**
     * This interface must be implemented by activities that contain this
     * fragment to allow an interaction in this fragment to be communicated
     * to the activity and potentially other fragments contained in that
     * activity.
     * <p/>
     * See the Android Training lesson <a href=
     * "http://developer.android.com/training/basics/fragments/communicating.html"
     * >Communicating with Other Fragments</a> for more information.
     */
    public interface OnViewMessageListener
    {
        public void onSendReply(int uid, String uname);
        public void onViewTask(int taskid);
    }

    protected class MarkRead extends AsyncTask<Void, Void, JSONObject>
    {
        @Override
        protected JSONObject doInBackground(Void... params) {
            try
            {
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_mark_read));
                List<NameValuePair> nvp = new ArrayList<NameValuePair>();
                nvp.add(new BasicNameValuePair("messageid", Integer.toString(mMessageID)));
                post.setEntity(new UrlEncodedFormEntity(nvp));
                String resp = EntityUtils.toString(client.execute(post).getEntity());
                System.err.println(resp);
                new JSONObject(resp);
            }
            catch (Throwable t)
            {
                t.printStackTrace();
            }
            return null;
        }
    }
}
