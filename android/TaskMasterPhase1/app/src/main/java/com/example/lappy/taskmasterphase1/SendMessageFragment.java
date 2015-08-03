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
import android.widget.TextView;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.util.ArrayList;
import java.util.List;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link SendMessageFragment.OnSendMessageInteractionListener} interface
 * to handle interaction events.
 * Use the {@link SendMessageFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class SendMessageFragment extends Fragment {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_USERID = "userid";
    private static final String ARG_USERNAME = "username";

    // TODO: Rename and change types of parameters
    private int mUserID;
    private String mUsername;

    private EditText mMessageContent;

    private OnSendMessageInteractionListener mListener;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param userid Parameter 1.
     * @param username Parameter 2.
     * @return A new instance of fragment SendMessageFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static SendMessageFragment newInstance(int userid,String username) {
        SendMessageFragment fragment = new SendMessageFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_USERID,userid);
        args.putString(ARG_USERNAME,username);
        fragment.setArguments(args);
        return fragment;
    }

    public SendMessageFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mUserID = getArguments().getInt(ARG_USERID);
            mUsername = getArguments().getString(ARG_USERNAME);
        }

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.fragment_send_message, container, false);
        // Inflate the layout for this fragment

        TextView username = (TextView)v.findViewById(R.id.textViewSendMessageUsername);
        username.setText(mUsername);

        mMessageContent = (EditText)v.findViewById(R.id.editTextSendMessageContent);

        Button send = (Button)v.findViewById(R.id.buttonSendMessageSend);
        send.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new SendMessage().execute();
            }
        });

        return v;
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnSendMessageInteractionListener) activity;
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
    public interface OnSendMessageInteractionListener {
        // TODO: Update argument type and name
        public void onMessageSent(Uri uri);
    }

    protected class SendMessage extends AsyncTask<Void, Void, JSONObject>
    {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
        }

        @Override
        protected void onPostExecute(JSONObject jsonObject) {
            if(jsonObject !=null && jsonObject.has("Success"))
            {
                mListener.onMessageSent(null);
            }
            super.onPostExecute(jsonObject);
        }

        @Override
        protected JSONObject doInBackground(Void... params) {
            try
            {
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_send_message));

                List<NameValuePair> nvp = new ArrayList<NameValuePair>();
                nvp.add(new BasicNameValuePair("receiverID",Integer.toString(mUserID)));
                nvp.add(new BasicNameValuePair("content", mMessageContent.getText().toString()));

                post.setEntity(new UrlEncodedFormEntity(nvp));

                HttpResponse httpResponse = client.execute(post);
                HttpEntity entity = httpResponse.getEntity();


                String resp = EntityUtils.toString(entity);
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
