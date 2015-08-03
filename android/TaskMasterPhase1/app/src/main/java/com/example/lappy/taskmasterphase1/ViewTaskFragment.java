package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.content.Entity;
import android.gesture.Gesture;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.text.Layout;
import android.view.GestureDetector;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RatingBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ViewFlipper;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.concurrent.ExecutionException;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link ViewTaskFragment.OnPlaceBidListener} interface
 * to handle interaction events.
 * Use the {@link ViewTaskFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class ViewTaskFragment extends Fragment {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_TASKID = "taskid";
    //private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private int mTaskID;
    //private String mParam2;

    private OnPlaceBidListener mListener;
    private OnViewUserListener mViewUserListener;

    private ViewFlipper mViewFlipper;

    private GestureDetector mGestureDetector;

    private JSONObject mTaskInfo;

    private Task mTask;
    private User mUser;

    private int[] galleryPics = { R.drawable.car1,
                                R.drawable.carwash2,
                                R.drawable.carwash3};
    private static Button mBidButton;

    private LinearLayout mMasterLayout;

    private ProgressBar mProgressBar;

    private EditText mPlaceBid;
    private TextView mCurrentBid;

    private int mWinnerID;
    private String mWinnerUsername;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param taskid com.example.lappy.taskmasterphase1.Task ID.
     * //@param param2 Parameter 2.
     * @return A new instance of fragment ViewTaskFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static ViewTaskFragment newInstance(int taskid) {
        ViewTaskFragment fragment = new ViewTaskFragment();
        Bundle args = new Bundle();
        //args.putString(ARG_PARAM1, param1);
        //args.putString(ARG_PARAM2, param2);
        args.putInt(ARG_TASKID, taskid);
        fragment.setArguments(args);
        return fragment;
    }

    public ViewTaskFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
           // mParam1 = getArguments().getString(ARG_PARAM1);
            mTaskID = getArguments().getInt(ARG_TASKID);
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_view_task, container, false);

        mMasterLayout = (LinearLayout)view.findViewById(R.id.viewTaskLayout);
        mTask = new Task();
        mUser = new User();

        System.err.println("taskid = "+mTaskID);

        TextView taskTitle = (TextView)view.findViewById(R.id.textViewTaskTitle);
        TextView taskUser = (TextView)view.findViewById(R.id.textViewTaskUser);
        TextView taskBid = (TextView)view.findViewById(R.id.textViewCurrentBidAmt);
        TextView taskContent = (TextView)view.findViewById(R.id.textViewTaskContent);

        //Set bid to place
        final EditText placeBid = (EditText)view.findViewById(R.id.placeBidEditText);
        final TextView currentBid = (TextView)view.findViewById(R.id.textViewCurrentBidAmt);

        mPlaceBid = (EditText)view.findViewById(R.id.placeBidEditText);
        mCurrentBid = (TextView)view.findViewById(R.id.textViewCurrentBidAmt);

        RatingBar listerRating = (RatingBar)view.findViewById(R.id.ratingBarLister);
        ImageView listerAvatar = (ImageView)view.findViewById(R.id.imageViewListerAvatar);
        TextView timeLeft = (TextView)view.findViewById(R.id.textViewTaskTimeRemaining);

        Map<String,View> views = new HashMap<String,View>();
        views.put("taskTitle",taskTitle);
        views.put("taskUser",taskUser);
        views.put("taskContent",taskContent);
        views.put("taskBid", taskBid);
        views.put("listerRating", listerRating);
        views.put("listerAvatar",listerAvatar);
        views.put("timeLeft",timeLeft);
        //views.put("currentBid",currentBid);

        taskTitle.setText(mTask.getTitle());
        taskUser.setText(mUser.getName());
        taskBid.setText(mTask.getDescription());
        taskContent.setText(mTask.getDescription());
        currentBid.setText("$" + mTask.getCurBid());

        //mProgressLayout = (RelativeLayout)view.findViewById(R.id.layoutTaskSpinner);
        mProgressBar = (ProgressBar)view.findViewById(R.id.progressBarTask);

        ViewGroup userInfo = (ViewGroup) view.findViewById(R.id.layout_user_info);
        userInfo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //Toast.makeText(v.getContext(),"User info",Toast.LENGTH_LONG).show();
                mViewUserListener.onViewUser(mUser.getId());
            }
        });


        /*Double currentBidAmt = Double.parseDouble(currentBid.getText().toString());
        currentBidAmt = currentBidAmt - 0.01;*/
        //placeBid.setText(currentBid.getText().toString().substring(1));
        //TODO: Make bidding actually do something

        mBidButton = (Button)view.findViewById(R.id.button);

        mBidButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mBidButton.post(new Runnable() {
                    public void run() {
                        //currentBid.setText("$" + placeBid.getText().toString());
                        if(MainActivity.ismLoggedIn())
                        {
                            new PlaceBid().execute(Integer.decode(placeBid.getText().toString()));
                            //mListener.onPlaceBid(true);
                        }
                        else
                        {
                            //mListener.onPlaceBid(Double.parseDouble(placeBid.getText().toString()));
                            mListener.onPlaceBid(false);
                        }

                    }
                });
            }
        });

        // Inflate the layout for this fragment


        mViewFlipper = (ViewFlipper)view.findViewById(R.id.viewFlipper);

        /*
        for (int i = 0; i < galleryPics.length; i++) {
            ImageView imageView = new ImageView(view.getContext());
            imageView.setImageResource(galleryPics[i]);
            mViewFlipper.addView(imageView);
        }*/

        mViewFlipper.setInAnimation(view.getContext(), android.R.anim.fade_in);
        mViewFlipper.setOutAnimation(view.getContext(), android.R.anim.fade_out);

        mViewFlipper.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                mGestureDetector.onTouchEvent(event);
                //Toast.makeText(getView().getContext(), "ay bruh",Toast.LENGTH_LONG).show();
                return true;
            }
        });

        GalleryGestureDetector customGestureDetector = new GalleryGestureDetector();
        mGestureDetector = new GestureDetector(view.getContext(), customGestureDetector);

        new GetTaskInfo().execute(views);

        return view;


    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);

        try {
            mListener = (OnPlaceBidListener) activity;
            mViewUserListener = (OnViewUserListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnPlaceBidListener and OnViewUserListener");
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

    public interface OnPlaceBidListener
    {
        public void onPlaceBid(boolean loggedIn);

        public void leaveFeedback(int revieweeuid, int taskid, String username, boolean listerOrDoer);
    }

    public interface OnViewUserListener
    {
        public void onViewUser(int userid);
    }

    private class GalleryGestureDetector extends GestureDetector.SimpleOnGestureListener
    {
        @Override
        public boolean onFling(MotionEvent e1, MotionEvent e2, float velocityX, float velocityY) {

            // Swipe left (next)
            if (e1.getX() > e2.getX()) {

                //mViewFlipper.setInAnimation(getView().getContext(), android.R.anim.fade_in);
                //mViewFlipper.setOutAnimation(getView().getContext(), android.R.anim.fade_out);

                mViewFlipper.showNext();

            }

            // Swipe right (previous)
            if (e1.getX() < e2.getX()) {

                //mViewFlipper.setInAnimation(getView().getContext(), android.R.anim.slide_out_right);
                //mViewFlipper.setOutAnimation(getView().getContext(), android.R.anim.fade_out);
                mViewFlipper.showPrevious();
            }

            //Toast.makeText(getView().getContext(),"we have flung",Toast.LENGTH_LONG).show();

            return super.onFling(e1, e2, velocityX, velocityY);
        }

        /*
        @Override
        public boolean onSingleTapConfirmed(MotionEvent e) {
            mViewFlipper.showNext();
            return super.onSingleTapConfirmed(e);
        }*/
    }

    protected class PlaceBid extends AsyncTask<Integer, Void, JSONObject>
    {
        protected int bidAmt;

        @Override
        protected JSONObject doInBackground(Integer... params) {
            bidAmt = params[0];
            return placeBid(bidAmt);
        }

        @Override
        protected void onPostExecute(JSONObject jsonObject) {
            super.onPostExecute(jsonObject);
            if(jsonObject==null)
            {
                mPlaceBid.setError("Unknown error");
            }
            else
            {
                if(jsonObject.has("active"))
                {
                    mPlaceBid.setError("This auction is over!");
                }
                else if(jsonObject.has("bidamount"))
                {
                    mPlaceBid.setError("You must bid lower than the current bid!");
                }
                else
                {
                    mCurrentBid.setText("$"+bidAmt+".00");
                    mCurrentBid.invalidate();
                    mListener.onPlaceBid(true);
                }
            }

            mPlaceBid.invalidate();
        }

        protected JSONObject placeBid(int amt)
        {
            try
            {
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_place_bid));
                List<NameValuePair> nvp = new ArrayList<NameValuePair>();
                nvp.add(new BasicNameValuePair("bidamt", Integer.toString(amt)));
                nvp.add(new BasicNameValuePair("taskid",Integer.toString(mTaskID)));
                nvp.add(new BasicNameValuePair("bidderid",Integer.toString(MainActivity.getmUserID())));
                post.setEntity(new UrlEncodedFormEntity(nvp));
                HttpResponse resp = client.execute(post);
                HttpEntity entity = resp.getEntity();
                //System.err.println(EntityUtils.toString(entity));
                return new JSONObject(EntityUtils.toString(entity));
            }
            catch (Throwable t)
            {
                t.printStackTrace();
            }
            return null;
        }
    }

    protected class GetTaskInfo extends AsyncTask<Map<String,View>, Void, JSONObject>
    {
        Map aViews;
        Bitmap avatar;
        Bitmap[] gallery;

        @Override
        protected JSONObject doInBackground(Map<String,View>... params) {
            System.err.println("getting task info");
            aViews = params[0];
            return getTaskInfo();
        }

        @Override
        protected void onPostExecute(JSONObject jsonObject) {
            super.onPostExecute(jsonObject);
            //mProgressLayout.setVisibility(View.INVISIBLE);
            mProgressBar.setVisibility(View.INVISIBLE);
            mMasterLayout.setVisibility(View.VISIBLE);
            try
            {
                if(!jsonObject.has("error"))
                {
                    mTask.setTitle(jsonObject.getString("Title"));
                    mTask.setDescription(jsonObject.getString("Description"));
                    mTask.setCurBid(jsonObject.getInt("CurrentBid"));
                    if(jsonObject.has("EndDateTime") && jsonObject.getString("EndDateTime")!="null")
                    {
                        mTask.setEndTime(jsonObject.getInt("EndDateTime"));
                    }

                    //mTask.setImgUrl("");

                    mUser.setId(jsonObject.getInt("Lister"));
                    mUser.setName(jsonObject.getString("ListerUsername"));
                    //mUser.setAvatarUrl(getString(R.string.url_taskmaster_main) + jsonObject.getString("ListerAvatarURL"));
                    mUser.setRating(jsonObject.getDouble("ListerRating"));
                    mUser.setRatingWeight(jsonObject.getInt("ListerRatingWeight"));

                    ((TextView)aViews.get("taskTitle")).setText(mTask.getTitle());
                    ((TextView)aViews.get("taskContent")).setText(mTask.getDescription());
                    ((TextView)aViews.get("taskUser")).setText(mUser.getName()+" ("+mUser.getRatingWeight()+")");
                    ((TextView)aViews.get("taskBid")).setText("$" + mTask.getCurBid() + ".00");
                    ((ImageView)aViews.get("listerAvatar")).setImageBitmap(avatar);
                    ((RatingBar)aViews.get("listerRating")).setRating((float) mUser.getRating());
                    ((TextView)aViews.get("timeLeft")).setText(mTask.getTimeLeftString());
                    mPlaceBid.setText(Integer.toString(mTask.getCurBid()));

                    for (int i = 0; i < gallery.length; i++) {
                        ImageView imageView = new ImageView(getView().getContext());
                        imageView.setImageBitmap(gallery[i]);
                        mViewFlipper.addView(imageView);
                    }

                    if(jsonObject.has("HighestBidderUsername"))
                    {
                        mWinnerID = jsonObject.getInt("HighestBidderID");
                        mWinnerUsername = jsonObject.getString("HighestBidderUsername");
                    }

                    if(!mTask.isActive())
                    {
                        System.err.println("task uid: "+mUser.getId()+" uid: "+MainActivity.getmUserID());
                        if(mUser.getId() == MainActivity.getmUserID())
                        {
                            mBidButton.setText("Leave Feedback");
                            mBidButton.setOnClickListener(new View.OnClickListener() {
                                @Override
                                public void onClick(View v) {
                                    mListener.leaveFeedback(mWinnerID,mTaskID,mWinnerUsername,false);
                                }
                            });
                        }
                        else if (mWinnerID == MainActivity.getmUserID())
                        {
                            mBidButton.setText("Leave Feedback");
                            mBidButton.setOnClickListener(new View.OnClickListener() {
                                @Override
                                public void onClick(View v) {
                                    mListener.leaveFeedback(mUser.getId(),mTaskID,mUser.getName(),true);
                                }
                            });
                        }
                        else
                        {
                            mBidButton.setClickable(false);
                            mBidButton.setTextColor(Color.GRAY);
                            mBidButton.setText("auction ended");
                        }

                    }

                    mMasterLayout.invalidate();
                }
                else
                {
                    System.err.println("json err");
                    if(jsonObject.has("taskid"))
                    {
                        System.err.println("taskid error");
                    }
                }
            }
            catch(Throwable t)
            {
                t.printStackTrace();
            }
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            //mProgressLayout.setVisibility(View.VISIBLE);
            mProgressBar.setVisibility(View.VISIBLE);
            mMasterLayout.setVisibility(View.INVISIBLE);
        }

        protected JSONObject getTaskInfo()
        {
            try
            {
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_view_task));

                List<NameValuePair> nvp = new ArrayList<>();
                nvp.add(new BasicNameValuePair("TaskID",Integer.toString(mTaskID)));
                post.setEntity(new UrlEncodedFormEntity(nvp));

                HttpResponse resp = client.execute(post);
                HttpEntity entity = resp.getEntity();


                String entityString = EntityUtils.toString(entity);

                System.err.println(entityString);

                JSONObject jsonTaskInfo = new JSONObject(entityString);



                //Load user avatar
                if(jsonTaskInfo.has("ListerAvatarURL"))
                {
                    System.err.println("userid: " + jsonTaskInfo.getInt("Lister"));
                    mUser.setAvatarUrl(getString(R.string.url_taskmaster_main) + jsonTaskInfo.getString("ListerAvatarURL"));
                    //mUser.setAvatarUrl(jsonTaskInfo.getString("ListerAvatarURL"));
                    InputStream bmpIn = new java.net.URL(mUser.getAvatarUrl()).openStream();
                    System.err.println(mUser.getAvatarUrl());
                    avatar = BitmapFactory.decodeStream(bmpIn);
                }

                //Load gallery
                if(jsonTaskInfo.has("NumImages"))
                {
                    gallery = new Bitmap[jsonTaskInfo.getInt("NumImages")];
                    for(int i=0;i<gallery.length;i++)
                    {
                        String bmpString = getString(R.string.url_task_gallery)+mTaskID+"/"+i+".jpg";
                        System.err.println(bmpString);
                        InputStream bmpIn = new java.net.URL(bmpString).openStream();
                        gallery[i] = BitmapFactory.decodeStream(bmpIn);
                    }
                }

                return jsonTaskInfo;
            }
            catch(Throwable t)
            {
                t.printStackTrace();
            }
            return null;
        }
    }
}
