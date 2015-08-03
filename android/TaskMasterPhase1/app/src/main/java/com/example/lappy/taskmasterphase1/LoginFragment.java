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
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutionException;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link LoginFragment.OnLoginInteractionListener} interface
 * to handle interaction events.
 * Use the {@link LoginFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class LoginFragment extends Fragment {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_USERNAME = "username";
    private static final String ARG_PASSWORD = "password";

    // TODO: Rename and change types of parameters
    private String mUsername = null;
    private String mPassword = null;

    private EditText mEditTextUsername;
    private EditText mEditTextPassword;

    private OnLoginInteractionListener mListener;

    private boolean mLoginSuccess = false;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param username User name.
     * @param password Password.
     * @return A new instance of fragment LoginFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static LoginFragment newInstance(String username, String password) {
        LoginFragment fragment = new LoginFragment();
        Bundle args = new Bundle();
        args.putString(ARG_USERNAME, username);
        args.putString(ARG_PASSWORD, password);
        fragment.setArguments(args);
        return fragment;
    }

    public LoginFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mUsername = getArguments().getString(ARG_USERNAME);
            mPassword = getArguments().getString(ARG_PASSWORD);
        }


    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View ret = inflater.inflate(R.layout.fragment_login, container, false);
        mEditTextUsername = (EditText)ret.findViewById(R.id.editTextLoginUser);
        mEditTextPassword = (EditText)ret.findViewById(R.id.editTextLoginPass);
        if(mUsername!=null)
        {
            mEditTextUsername.setText(mUsername);
        }
        if (mPassword!=null)
        {
            mEditTextPassword.setText(mPassword);
        }

        Button loginButton = (Button) ret.findViewById(R.id.buttonLogin);
        loginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    new LoginToServer().execute(new String[]{mEditTextUsername.getText().toString(), mEditTextPassword.getText().toString()});
                } catch (Exception e) {
                    e.printStackTrace();
                    return;
                }

            }
        });

        Button registerButton = (Button) ret.findViewById(R.id.buttonRegister);
        registerButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                register(null);
            }
        });

        TextView errorBox = (TextView)ret.findViewById(R.id.textViewLoginError);
        errorBox.setText("");

        return ret;
    }


    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnLoginInteractionListener) activity;
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
    public interface OnLoginInteractionListener {
        public void onLoginInteraction(String s);
    }

    public void login(View v)
    {
        if(mListener != null)
        {
            mListener.onLoginInteraction("login");
        }
    }

    public void register(View v)
    {
        if(mListener!=null)
        {
            mListener.onLoginInteraction("register");
        }
    }

    protected class LoginToServer extends AsyncTask<String, Void, JSONObject>
    {
        TextView aErrorBox;

        @Override
        protected void onPostExecute(JSONObject s) {
            if(s!=null)
            {
                if(s.has("userid"))
                {
                    aErrorBox.post(new Runnable() {
                        @Override
                        public void run() {
                            aErrorBox.setText("Success! Welcome to Task Master");
                        }
                    });

                    try
                    {
                        MainActivity.setmUserID(s.getInt("userid"));
                        MainActivity.setmUserName(s.getString("username"));
                    }
                    catch(Throwable t)
                    {
                        t.printStackTrace();
                    }

                    login(getView());
                }
                else if(s.has("password"))
                {
                    mEditTextPassword.setError(getString(R.string.error_msg_bad_password));
                }
                else if(s.has("username"))
                {
                    mEditTextUsername.setError(getString(R.string.error_msg_bad_username));
                }
            }
        }

        @Override
        protected JSONObject doInBackground(String... params) {
            aErrorBox = (TextView)getView().findViewById(R.id.textViewLoginError);

            try
            {
                String username = mEditTextUsername.getText().toString();
                String password = mEditTextPassword.getText().toString();

                HttpClient httpClient = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_login));

                List<NameValuePair> nvp = new ArrayList<NameValuePair>();
                nvp.add(new BasicNameValuePair("username",username));
                nvp.add(new BasicNameValuePair("password", password));
                post.setEntity(new UrlEncodedFormEntity(nvp));

                String resp = EntityUtils.toString(httpClient.execute(post).getEntity());
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

   /* protected class LoginToServer extends AsyncTask<String, Void, String>
    {
        @Override
        protected void onPostExecute(String s) {
            if(s.equals("success"))
            {
                mLoginSuccess=true;
                login(getView());
            }
        }

        @Override
        protected String doInBackground(String... params) {
            doLogin(params[0],params[1]);
            return null;
        }

        protected String doLogin(String username, String password)
        {
            final TextView errorBox = (TextView)getView().findViewById(R.id.textViewLoginError);

            //EditText username = (EditText)getView().findViewById(R.id.editTextLoginUser);
            //EditText password = (EditText)getView().findViewById(R.id.editTextLoginPass);

            HttpClient httpClient = MainActivity.getmHttpClient();

            List<NameValuePair> nvp = new ArrayList<NameValuePair>();
            nvp.add(new BasicNameValuePair("username",username));
            nvp.add(new BasicNameValuePair("password",password));

            HttpPost post = new HttpPost(getString(R.string.url_login));
            try {
                errorBox.post(new Runnable() {
                    @Override
                    public void run() {
                        errorBox.setText("encoding post");
                    }
                });
                post.setEntity(new UrlEncodedFormEntity(nvp));
            } catch (UnsupportedEncodingException e) {
                e.printStackTrace();
            }

            HttpResponse resp;
            HttpEntity entity;

            try {
                errorBox.post(new Runnable() {
                    @Override
                    public void run() {
                        errorBox.setText("sending data");
                    }
                });
                resp = httpClient.execute(post);
            } catch (IOException e) {
                e.printStackTrace();
                return "fail";
            }

            entity = resp.getEntity();

            final JSONObject objResponse;

            try {
                errorBox.post(new Runnable() {
                    @Override
                    public void run() {
                        errorBox.setText("decoding json");
                    }
                });
                objResponse = new JSONObject(EntityUtils.toString(entity));
            } catch (Exception e) {
                e.printStackTrace();
                errorBox.post(new Runnable() {
                    @Override
                    public void run() {
                        errorBox.setText("json error");
                    }
                });
                return "fail";
            }

            final String errorString;

            if(objResponse.has("userid"))
            {
                errorString = getString(R.string.error_logged_in);
                try {
                    MainActivity.setmUserID(objResponse.getInt("userid"));
                    MainActivity.setmUserName(objResponse.getString("username"));
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                //mLoginSuccess = true;
                return "success";

            }
            else if(objResponse.has("password"))
            {
                errorString = getString(R.string.error_msg_bad_password);
            }
            else
            {
                errorString = getString(R.string.error_msg_bad_username);

            }

            errorBox.post(new Runnable() {
                @Override
                public void run() {
                    errorBox.setText(errorString);
                }
            });
            return "fail";
        }
    }*/

}
