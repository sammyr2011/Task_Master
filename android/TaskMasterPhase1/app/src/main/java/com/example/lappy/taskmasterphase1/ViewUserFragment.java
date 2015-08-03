package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.content.Entity;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.RatingBar;
import android.widget.TextView;

import org.apache.http.HttpEntity;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

import java.io.InputStream;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Random;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link com.example.lappy.taskmasterphase1.ViewUserFragment.OnViewUserListener} interface
 * to handle interaction events.
 * Use the {@link ViewUserFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class ViewUserFragment extends Fragment {
    private List<Feedback> mListerFeedback;
    private List<Feedback> mDoerFeedback;

    private FeedbackListAdapter mListerFeedbackAdapter;
    private FeedbackListAdapter mDoerFeedbackAdapter;

    private static final String ARG_USERID = "userid";

    private ImageView mUserAvatar;

    private int mUserID;

    private String mUsername;

    private OnViewUserListener mListener;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param userid Parameter 1.
     * @return A new instance of fragment ViewUserFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static ViewUserFragment newInstance(int userid) {
        ViewUserFragment fragment = new ViewUserFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_USERID, userid);
        fragment.setArguments(args);
        return fragment;
    }

    public ViewUserFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mUserID = getArguments().getInt(ARG_USERID);
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View v = inflater.inflate(R.layout.fragment_view_user, container, false);


        TextView username = (TextView)v.findViewById(R.id.textViewViewUserUsername);
        TextView listerFeedbackTitle = (TextView)v.findViewById(R.id.textViewListerFeedback);
        final ListView listerFeedback = (ListView)v.findViewById(R.id.listViewListerFeedback);
        RatingBar listerFeedbackRating = (RatingBar)v.findViewById(R.id.ratingBarListerFeedback);
        TextView doerFeedbackTitle = (TextView)v.findViewById(R.id.textViewDoerFeedback);
        ListView doerFeedback = (ListView)v.findViewById(R.id.listViewDoerFeedback);
        RatingBar doerFeedbackRating = (RatingBar)v.findViewById(R.id.ratingBarDoerFeedback);



        mListerFeedback = new ArrayList<Feedback>();
        mListerFeedbackAdapter = new FeedbackListAdapter(v.getContext(),mListerFeedback);
        listerFeedback.setAdapter(mListerFeedbackAdapter);
        listerFeedback.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                mListener.onViewTaskFromViewUser(mListerFeedback.get(position).getTaskid());
            }
        });

        mDoerFeedback = new ArrayList<Feedback>();
        mDoerFeedbackAdapter = new FeedbackListAdapter(v.getContext(),mDoerFeedback);
        doerFeedback.setAdapter(mDoerFeedbackAdapter);
        doerFeedback.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                mListener.onViewTaskFromViewUser(mDoerFeedback.get(position).getTaskid());
            }
        });

        HashMap<String,View> viewMap = new HashMap<String,View>();
        viewMap.put("username",username);
        viewMap.put("listerFeedbackTitle", listerFeedbackTitle);
        viewMap.put("listerFeedbackRating", listerFeedbackRating);
        viewMap.put("listerFeedback", listerFeedback);
        viewMap.put("doerFeedbackTitle", doerFeedbackTitle);
        viewMap.put("doerFeedback", doerFeedback);
        viewMap.put("doerFeedbackRating", doerFeedbackRating);

        Button sendMessage = (Button)v.findViewById(R.id.buttonViewUserSendMessage);
        sendMessage.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(mUsername!=null)
                {
                    mListener.onSendMessageFromViewUser(mUserID, mUsername);
                }
            }
        });

        mUserAvatar = (ImageView)v.findViewById(R.id.imageViewViewUserAvatar);

        new GetUserData().execute(viewMap);

        return v;
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnViewUserListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnViewUserListener");
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
    public interface OnViewUserListener {
        // TODO: Update argument type and name
        public void onSendMessageFromViewUser(int userid, String username);
        public void onViewTaskFromViewUser(int taskid);
    }

    protected class GetUserData extends AsyncTask<Map<String,View>,Void,JSONObject>
    {

        protected Map<String,View> aViews;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
        }

        @Override
        protected void onPostExecute(JSONObject jsonObject) {
            try{
                if(jsonObject!=null)
                {
                    if(jsonObject.has("Username"))
                    {
                        ((TextView)aViews.get("username")).setText(jsonObject.getString("Username"));
                        mUsername = jsonObject.getString("Username");
                    }

                    if(jsonObject.has("ListerReviews")) {
                        float average = 0;
                        JSONArray reviews = jsonObject.getJSONArray("ListerReviews");
                        //mListerFeedback = new ArrayList<Feedback>();
                        for (int i = 0; i < reviews.length(); i++) {
                            System.err.println(reviews.get(i));
                            Feedback tmp = new Feedback();
                            if(reviews.getJSONObject(i).has("rating") && !reviews.getJSONObject(i).getString("rating").equals("null"))
                            {
                                tmp.setRating(reviews.getJSONObject(i).getDouble("rating"));
                            }

                            if(reviews.getJSONObject(i).has("comment") && !reviews.getJSONObject(i).getString("comment").equals("null"))
                            {
                                tmp.setContent(reviews.getJSONObject(i).getString("comment"));
                            }

                            if(reviews.getJSONObject(i).has("timestamp")&&!reviews.getJSONObject(i).getString("timestamp").equals("null"))
                            {
                                tmp.setTimestamp(reviews.getJSONObject(i).getInt("timestamp"));
                            }

                            if(reviews.getJSONObject(i).has("taskid")&&!reviews.getJSONObject(i).getString("taskid").equals("null"))
                            {
                                tmp.setTaskid(reviews.getJSONObject(i).getInt("taskid"));
                            }
                            average += tmp.getRating();
                            mListerFeedback.add(tmp);
                            //mListerFeedback.add(new Feedback());
                        }
                        average /= reviews.length();
                        ((RatingBar) aViews.get("listerFeedbackRating")).setRating(average);
                        mListerFeedbackAdapter.notifyDataSetChanged();
                        ((TextView) aViews.get("listerFeedbackTitle")).setText("Lister Feedback (" + reviews.length() + ")");
                    }

                    if(jsonObject.has("DoerReviews")) {
                        float average = 0;
                        JSONArray reviews = jsonObject.getJSONArray("DoerReviews");
                        //mListerFeedback = new ArrayList<Feedback>();
                        for (int i = 0; i < reviews.length(); i++) {
                            System.err.println(reviews.get(i));
                            Feedback tmp = new Feedback();
                            if(reviews.getJSONObject(i).has("rating") && !reviews.getJSONObject(i).getString("rating").equals("null"))
                            {
                                tmp.setRating(reviews.getJSONObject(i).getDouble("rating"));
                            }

                            if(reviews.getJSONObject(i).has("comment") && !reviews.getJSONObject(i).getString("comment").equals("null"))
                            {
                                tmp.setContent(reviews.getJSONObject(i).getString("comment"));
                            }

                            if(!reviews.getJSONObject(i).getString("timestamp").equals("null"))
                            {
                                tmp.setTimestamp(reviews.getJSONObject(i).getInt("timestamp"));
                            }

                            if(reviews.getJSONObject(i).has("taskid")&&!reviews.getJSONObject(i).getString("taskid").equals("null"))
                            {
                                tmp.setTaskid(reviews.getJSONObject(i).getInt("taskid"));
                            }
                            average += tmp.getRating();
                            mDoerFeedback.add(tmp);
                            //mListerFeedback.add(new Feedback());
                        }
                        average /= reviews.length();
                        ((RatingBar) aViews.get("doerFeedbackRating")).setRating(average);
                        mDoerFeedbackAdapter.notifyDataSetChanged();
                        ((TextView) aViews.get("doerFeedbackTitle")).setText("Doer Feedback (" + reviews.length() + ")");
                    }

                    if(jsonObject.has("AvatarURL"))
                    {
                        new LoadAvatar().execute(jsonObject.getString("AvatarURL"));
                    }
                }
            }
            catch(Throwable t)
            {
                t.printStackTrace();
            }
        }

        @Override
        protected JSONObject doInBackground(Map<String,View>... params) {
            aViews = params[0];

            try{
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_view_user));
                List<NameValuePair> nvp = new ArrayList<NameValuePair>();
                nvp.add(new BasicNameValuePair("userid", Integer.toString(mUserID)));
                post.setEntity(new UrlEncodedFormEntity(nvp));
                HttpEntity entity = client.execute(post).getEntity();
                String out = EntityUtils.toString(entity);
                System.err.println(out);
                JSONObject ret = new JSONObject(out);

                return ret;
            }
            catch(Throwable t)
            {
                t.printStackTrace();
            }
            return null;
        }
    }

    protected class LoadAvatar extends AsyncTask<String, Void, Bitmap>
    {
        @Override
        protected void onPostExecute(Bitmap bitmap) {
            if(bitmap!=null)
            {
                mUserAvatar.setImageBitmap(bitmap);
                mUserAvatar.invalidate();
            }
        }

        @Override
        protected Bitmap doInBackground(String... params) {
            try
            {
                InputStream bmpIn = new java.net.URL("http://travis-webserver.dyndns.org:81/"+params[0]).openStream();
                return BitmapFactory.decodeStream(bmpIn);
            }
            catch(Throwable t)
            {
                t.printStackTrace();
            }
            return null;
        }
    }
}
