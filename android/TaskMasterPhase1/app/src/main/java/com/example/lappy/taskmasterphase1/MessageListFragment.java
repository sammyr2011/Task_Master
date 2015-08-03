package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.os.*;
import android.support.v4.app.Fragment;
import android.os.Message;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListAdapter;
import android.widget.ProgressBar;
import android.widget.TextView;


import com.example.lappy.taskmasterphase1.dummy.DummyContent;

import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

/**
 * A fragment representing a list of Items.
 * <p/>
 * Large screen devices (such as tablets) are supported by replacing the ListView
 * with a GridView.
 * <p/>
 * Activities containing this fragment MUST implement the {@link OnFragmentInteractionListener}
 * interface.
 */
public class MessageListFragment extends Fragment implements AbsListView.OnItemClickListener {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    private OnMessageListListener mListener;

    private ProgressBar mProgressBar;

    /**
     * The fragment's ListView/GridView.
     */
    private AbsListView mListView;

    /**
     * The Adapter which will be used to populate the ListView/GridView with
     * Views.
     */
    private ListAdapter mAdapter;

    private List<com.example.lappy.taskmasterphase1.Message> mMessages;

    // TODO: Rename and change types of parameters
    public static MessageListFragment newInstance(String param1, String param2) {
        MessageListFragment fragment = new MessageListFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    /**
     * Mandatory empty constructor for the fragment manager to instantiate the
     * fragment (e.g. upon screen orientation changes).
     */
    public MessageListFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }

       /* // TODO: Change Adapter to display your content
        mAdapter = new ArrayAdapter<DummyContent.DummyItem>(getActivity(),
                android.R.layout.simple_list_item_1, android.R.id.text1, DummyContent.ITEMS);*/

        //mAdapter = new MessageListAdapter(getActivity(), mMessages);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_message, container, false);

        mMessages = new ArrayList<com.example.lappy.taskmasterphase1.Message>();

        mAdapter = new MessageListAdapter(view.getContext(),mMessages);

        // Set the adapter
        mListView = (AbsListView) view.findViewById(android.R.id.list);
        ///((AdapterView<ListAdapter>) mListView).setAdapter(mAdapter);
        mListView.setAdapter(mAdapter);

        // Set OnItemClickListener so we can be notified on item clicks
        mListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                System.err.println("click " + mMessages.size() + " clicked " + position);
                if (mMessages.get(position) == null) {
                    System.err.println("this shit on powder or something");
                } else {
                    System.err.println(mMessages.get(position).getMessage());
                }
                mListener.onOpenMessage(mMessages.get(position));
            }
        });

        mProgressBar = (ProgressBar)view.findViewById(R.id.progressBarMessageList);

        new GetMessages().execute();

        return view;
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnMessageListListener) activity;
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

    @Override
    public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
        /*
        if (null != mListener) {
            // Notify the active callbacks interface (the activity, if the
            // fragment is attached to one) that an item has been selected.
            mListener.onOpenMessage(mMessages.get(position));
        }*/
    }

    /**
     * The default content for this Fragment has a TextView that is shown when
     * the list is empty. If you would like to change the text, call this method
     * to supply the text it should use.
     */
    public void setEmptyText(CharSequence emptyText) {
        View emptyView = mListView.getEmptyView();

        if (emptyView instanceof TextView) {
            ((TextView) emptyView).setText(emptyText);
        }
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
    public interface OnMessageListListener {
        public void onOpenMessage(com.example.lappy.taskmasterphase1.Message m);
    }

    protected class GetMessages extends AsyncTask<Void, Void, JSONArray>
    {
        @Override
        protected void onPreExecute() {
            mProgressBar.setVisibility(View.VISIBLE);
            super.onPreExecute();
        }

        @Override
        protected void onPostExecute(JSONArray jsonArray) {
            mProgressBar.setVisibility(View.INVISIBLE);
            try
            {
                if(jsonArray!=null)
                {
                    for(int i=0;i<jsonArray.length();i++)
                    {
                        JSONObject tmpObj = jsonArray.getJSONObject(i);
                        com.example.lappy.taskmasterphase1.Message tmpMsg = new com.example.lappy.taskmasterphase1.Message();
                        tmpMsg.setMessage(tmpObj.getString("Content"));
                        tmpMsg.setSenderid(tmpObj.getInt("SenderID"));
                        tmpMsg.setUsername(" " + tmpObj.getString("SenderUsername") + " ");
                        tmpMsg.setTimestamp(tmpObj.getInt("Time"));
                        if(!tmpObj.getString("TaskID").equals("null"))
                        {
                            tmpMsg.setTaskid(tmpObj.getInt("TaskID"));
                        }
                        if(tmpObj.getInt("Read")==1)
                        {
                            tmpMsg.setRead(true);
                        }
                        else
                        {
                            tmpMsg.setRead(false);
                        }

                        mMessages.add(tmpMsg);
                        ((ArrayAdapter)mAdapter).notifyDataSetChanged();
                    }
                }

                Collections.sort(mMessages);
            }
            catch(Throwable throwable)
            {
                throwable.printStackTrace();
            }

        }

        @Override
        protected JSONArray doInBackground(Void... params) {
            try
            {
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_get_messages_simple));
                String resp = EntityUtils.toString(client.execute(post).getEntity());
                System.err.println(resp);
                return new JSONArray(resp);
            }
            catch(Throwable t)
            {
                t.printStackTrace();
            }
            return null;
        }
    }
}
