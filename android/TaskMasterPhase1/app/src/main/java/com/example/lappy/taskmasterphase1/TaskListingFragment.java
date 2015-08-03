package com.example.lappy.taskmasterphase1;

import android.app.Activity;
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
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;

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

import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.sql.Date;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;
import java.util.Random;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link com.example.lappy.taskmasterphase1.TaskListingFragment.OnTaskListingInteraction} interface
 * to handle interaction events.
 * Use the {@link TaskListingFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class TaskListingFragment extends Fragment {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_CATEGORY = "category";
    private static final String ARG_CATEGORY_NAME = "category_name";
    private static final String ARG_USERID = "userid";

    public void setmCategory(int mCategory) {
        this.mCategory = mCategory;
    }

    // TODO: Rename and change types of parameters
    private int mCategory;
    private String mCategoryName;

    private ProgressBar mProgressBar;

    private OnTaskListingInteraction mListener;

    private int mUserID=-1;

    //JSONArray mMaster;

    TaskListAdapter mAdapter;

    List<Task> mTasks;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param catid Parameter 1.
     * @param catname Category name.
     * @return A new instance of fragment TaskListingFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static TaskListingFragment newInstance(int catid, String catname) {
        TaskListingFragment fragment = new TaskListingFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_CATEGORY, catid);
        args.putString(ARG_CATEGORY_NAME, catname);
        fragment.setArguments(args);
        return fragment;
    }

    public static TaskListingFragment newInstance(int userid)
    {
        TaskListingFragment fragment = new TaskListingFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_USERID, userid);
        fragment.setArguments(args);
        return fragment;
    }

    public TaskListingFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            if(getArguments().containsKey(ARG_CATEGORY))
            {
                mCategory = getArguments().getInt(ARG_CATEGORY);
                mCategoryName = getArguments().getString(ARG_CATEGORY_NAME);
            }
            else if(getArguments().containsKey(ARG_USERID))
            {
                mUserID = getArguments().getInt(ARG_USERID);
            }
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_one_category_listing, container, false);

        mProgressBar = (ProgressBar)view.findViewById(R.id.progressBar2);

        mTasks = new ArrayList<Task>();
        mAdapter = new TaskListAdapter(view.getContext(),mTasks);
        ListView taskList = (ListView)view.findViewById(R.id.listViewTasks);
        taskList.setAdapter(mAdapter);
        taskList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                mListener.onSelectTask(mTasks.get(position).getTaskId());
            }
        });
        // Inflate the layout for this fragment

        /*try {
            new GetSubListings().execute("").get();
        } catch (Exception e)  {
            e.printStackTrace();
        }

        /*ArrayList<String> taskList = new ArrayList<String>();
        for(int i=0;i<mMaster.length();i++)
        {
            try {
                taskList.add(mMaster.getJSONObject(i).getString("title"));
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }

        ArrayList<Task> taskList = new ArrayList<Task>();
        for(int i=0;i<mMaster.length();i++)
        {
            try {
                taskList.add(new Task(mMaster.getJSONObject(i).getString("title"), 45.00, new Date(987987), "", mMaster.getJSONObject(i).getInt("taskid")));
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
        ListView tasks = (ListView)view.findViewById(R.id.listViewTasks);
        //tasks.setAdapter(new ArrayAdapter<String>(view.getContext(),android.R.layout.simple_list_item_1,taskList));
        tasks.setAdapter(new TaskListAdapter(view.getContext(),R.layout.task_in_list,taskList));
        tasks.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                try {
                    System.err.println(mMaster.getJSONObject(position).getInt("taskid"));
                    mListener.onSelectTask(mMaster.getJSONObject(position).getInt("taskid"));
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        });*/

        //Set title
        TextView categoryName = (TextView)view.findViewById(R.id.textViewCategoryName);
        categoryName.setText(mCategoryName);

        if(mUserID==-1)
        {
            new GetSubListings().execute("catid");
        }
        else
        {
            new GetSubListings().execute("userid");
        }

        return view;
    }


    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);

        try {
            mListener = (OnTaskListingInteraction) activity;
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
    public interface OnTaskListingInteraction {
        // TODO: Update argument type and name
        public void onSelectTask(int taskid);
    }

    protected class GetSubListings extends AsyncTask<String, Void, List<Task>>
    {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            mProgressBar.setVisibility(View.VISIBLE);
        }

        @Override
        protected void onPostExecute(List<Task> tasks) {
            if(tasks!=null)
            {
                mProgressBar.setVisibility(View.INVISIBLE);
                mTasks.addAll(tasks);
                Collections.sort(mTasks, new Comparator<Task>() {
                    @Override
                    public int compare(Task lhs, Task rhs) {
                        return lhs.getEndTime()-rhs.getEndTime();
                    }
                });
                mAdapter.notifyDataSetChanged();
            }

        }

        @Override
        protected List<Task> doInBackground(String... params) {
            try{
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_task_list));

                List<NameValuePair> nvp = new ArrayList<NameValuePair>();

                if(params[0].equals("catid"))
                {
                    nvp.add(new BasicNameValuePair("catid", Integer.toString(mCategory)));
                }
                else if(params[0].equals("userid"))
                {
                    nvp.add(new BasicNameValuePair("userid", Integer.toString(mUserID)));
                }



                post.setEntity(new UrlEncodedFormEntity(nvp));

                HttpResponse resp = client.execute(post);

                String respString = EntityUtils.toString(resp.getEntity());

                System.err.println(respString);

                JSONArray jsonTasks = new JSONArray(respString);

                List<Task> tasks = new ArrayList<Task>();

                for(int i=0;i<jsonTasks.length();i++)
                {
                    JSONObject taskJson = jsonTasks.getJSONObject(i);
                    System.err.println(taskJson.toString());
                    Task task = new Task();
                    if(taskJson.has("title") && !taskJson.getString("title").equals("null"))
                    {
                        task.setTitle(taskJson.getString("title"));
                    }
                    if(taskJson.has("currentbid") && !taskJson.getString("currentbid").equals("null"))
                    {
                        task.setCurBid(taskJson.getInt("currentbid"));
                    }
                    //task.setTimeLeft(new Date(System.currentTimeMillis() + 86400000 * (new Random().nextInt(Integer.SIZE - 1) % 14 + 1)));
                    if(taskJson.has("enddatetime"))
                    {
                        if(!taskJson.getString("enddatetime").equals("null"))
                        {
                            task.setEndTime(taskJson.getInt("enddatetime"));
                        }

                    }
                    if(taskJson.has("taskid") && !taskJson.getString("taskid").equals("null"))
                    {
                        task.setTaskId(taskJson.getInt("taskid"));
                    }
                    if(taskJson.has("numimg") && taskJson.getInt("numimg")>0)
                    {
                        InputStream bmpIn = new java.net.URL(getString(R.string.url_task_gallery)+task.getTaskId()+"/0.jpg").openStream();
                        Bitmap tmpBmp = BitmapFactory.decodeStream(bmpIn);
                        task.setThumbnail(tmpBmp);
                    }
                    tasks.add(task);
                }

                return tasks;
            }
            catch (Exception e)
            {
                e.printStackTrace();
            }
            return null;

        }
    }

}
