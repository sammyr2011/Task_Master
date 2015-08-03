package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RatingBar;
import android.widget.TextView;

import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link LeaveFeedbackFragment.OnLeaveFeedbackListener} interface
 * to handle interaction events.
 * Use the {@link LeaveFeedbackFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class LeaveFeedbackFragment extends Fragment {

    private static final String ARG_TASKID = "taskid";
    private static final String ARG_LISTERUSERNAME = "listerusername";
    private static final String ARG_REVIEWEEUID = "revieweeuid";
    private static final String ARG_LISTERORDOER = "listerordoer";


    private int mTaskID;
    private String mListerUsername;
    private int mRevieweeUID;

    private EditText mFeedbackContent;
    private RatingBar mFeedbackRating;

    private boolean mFeedbackForLister;

    private OnLeaveFeedbackListener mListener;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param taskid Parameter 1.
     * @param listerUsername Parameter 2.
     * @return A new instance of fragment LeaveFeedbackFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static LeaveFeedbackFragment newInstance(int revieweeuid, int taskid, String listerUsername, boolean listerOrDoer) {
        LeaveFeedbackFragment fragment = new LeaveFeedbackFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_TASKID,taskid);
        args.putString(ARG_LISTERUSERNAME, listerUsername);
        args.putBoolean(ARG_LISTERORDOER, listerOrDoer);
        args.putInt(ARG_REVIEWEEUID, revieweeuid);
        fragment.setArguments(args);
        return fragment;
    }

    public LeaveFeedbackFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mTaskID = getArguments().getInt(ARG_TASKID);
            mRevieweeUID = getArguments().getInt(ARG_REVIEWEEUID);
            mListerUsername = getArguments().getString(ARG_LISTERUSERNAME);
            mFeedbackForLister = getArguments().getBoolean(ARG_LISTERORDOER);
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.fragment_leave_feedback, container, false);
        // Inflate the layout for this fragment

        ((TextView)v.findViewById(R.id.textViewLeaveFeedbackUsername)).setText(mListerUsername);

        mFeedbackContent = (EditText)v.findViewById(R.id.editTextLeaveFeedbackContent);
        mFeedbackRating = (RatingBar)v.findViewById(R.id.ratingBarLeaveFeedback);

        Button submit = (Button)v.findViewById(R.id.buttonLeaveFeedbackSubmit);
        submit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new LeaveFeedback().execute();
            }
        });

        return v;
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnLeaveFeedbackListener) activity;
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
    public interface OnLeaveFeedbackListener {
        // TODO: Update argument type and name
        public void onFeedbackSuccess();
    }

    protected class LeaveFeedback extends AsyncTask<Void, Void, JSONObject>
    {
        @Override
        protected void onPostExecute(JSONObject jsonObject) {
            if(jsonObject!=null && jsonObject.has("Success"))
            {
                mListener.onFeedbackSuccess();
            }
        }

        @Override
        protected JSONObject doInBackground(Void... params) {
            try
            {
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_leave_feedback));
                List<NameValuePair> nvp = new ArrayList<NameValuePair>();
                nvp.add(new BasicNameValuePair("reviewee_uid",Integer.toString(mRevieweeUID)));
                nvp.add(new BasicNameValuePair("taskid", Integer.toString(mTaskID)));
                nvp.add(new BasicNameValuePair("comment", mFeedbackContent.getText().toString()));
                nvp.add(new BasicNameValuePair("rating", Integer.toString((int)mFeedbackRating.getRating())));
                //nvp.add(new BasicNameValuePair("listerOrDoer",mFeedbackForLister?"0":"1"));
                post.setEntity(new UrlEncodedFormEntity(nvp));
                String resp = EntityUtils.toString(client.execute(post).getEntity());
                System.err.println(resp);
                return new JSONObject(resp);
            }
            catch(Throwable t)
            {
                t.printStackTrace();
            }
            return null;
        }
    }

}
