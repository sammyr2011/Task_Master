package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.graphics.Color;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutionException;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link RegisterFragment.RegistrationListener} interface
 * to handle interaction events.
 * Use the {@link RegisterFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class RegisterFragment extends Fragment {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    private String mUsername;
    private String mPassword;

    private RegistrationListener mListener;

    private boolean mRegistrationSuccess = false;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment RegisterFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static RegisterFragment newInstance(String param1, String param2) {
        RegisterFragment fragment = new RegisterFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    public RegisterFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_register,container,false);

        Spinner states = (Spinner) view.findViewById(R.id.spinnerState);
        states.setAdapter(new ArrayAdapter<String>(getActivity(),
                android.R.layout.simple_list_item_1, android.R.id.text1, getResources().getStringArray(R.array.array_states)));
        // Inflate the layout for this fragment

        Button registerButton = (Button)view.findViewById(R.id.registerButton);
        registerButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    new RegisterToServer().execute().get();
                    if(mRegistrationSuccess)
                    {
                        mListener.onRegistrationInteraction(mUsername,mPassword);
                    }
                } catch (InterruptedException e) {
                    e.printStackTrace();
                } catch (ExecutionException e) {
                    e.printStackTrace();
                }
            }
        });

        return view;
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);

        try {
            mListener = (RegistrationListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement RegistrationListener");
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
    public interface RegistrationListener {
        // TODO: Update argument type and name
        public void onRegistrationInteraction(String username, String password);
    }

    protected class RegisterToServer extends AsyncTask<String, Void, String>
    {
        @Override
        protected String doInBackground(String... params) {
            reg();
            return null;
        }

        protected void reg()
        {
            setToBlack();

            HttpClient client = MainActivity.getmHttpClient();
            HttpPost post = new HttpPost(getString(R.string.url_register));

            EditText username = (EditText)getView().findViewById(R.id.editTextUserNameReg);
            EditText password = (EditText)getView().findViewById(R.id.editTextPassReg);
            EditText passwordverify = (EditText)getView().findViewById(R.id.editTextPassVerify);
            EditText firstname = (EditText)getView().findViewById(R.id.editTextFirstName);
            EditText lastname = (EditText)getView().findViewById(R.id.editTextLastName);
            EditText email = (EditText)getView().findViewById(R.id.editTextEmail);
            EditText address = (EditText)getView().findViewById(R.id.editTextStreetAddress);
            EditText city = (EditText)getView().findViewById(R.id.editTextCity);
            Spinner state = (Spinner)getView().findViewById(R.id.spinnerState);
            EditText zipcode = (EditText)getView().findViewById(R.id.editTextZIP);

            List<NameValuePair> nvp = new ArrayList<NameValuePair>();
            nvp.add(new BasicNameValuePair("username",username.getText().toString()));
            nvp.add(new BasicNameValuePair("password",password.getText().toString()));
            nvp.add(new BasicNameValuePair("passwordverify",passwordverify.getText().toString()));
            nvp.add(new BasicNameValuePair("firstname",firstname.getText().toString()));
            nvp.add(new BasicNameValuePair("lastname",lastname.getText().toString()));
            nvp.add(new BasicNameValuePair("email",email.getText().toString()));
            nvp.add(new BasicNameValuePair("address",address.getText().toString()));
            nvp.add(new BasicNameValuePair("city",city.getText().toString()));
            nvp.add(new BasicNameValuePair("state",state.getSelectedItem().toString()));
            nvp.add(new BasicNameValuePair("zipcode",zipcode.getText().toString()));

            try {
                post.setEntity(new UrlEncodedFormEntity(nvp));
            } catch (UnsupportedEncodingException e) {
                e.printStackTrace();
                Toast.makeText(getActivity().getBaseContext(),"post entity creation error",Toast.LENGTH_LONG).show();
                return;
            }

            HttpResponse resp;
            HttpEntity entity;

            try {
                resp = client.execute(post);
                entity = resp.getEntity();
            } catch (IOException e) {
                e.printStackTrace();
                Toast.makeText(getActivity().getBaseContext(),"post error",Toast.LENGTH_LONG).show();
                return;
            }

            JSONObject objResponse;

            try {
                objResponse = new JSONObject(EntityUtils.toString(entity));
            } catch (Exception e) {
                e.printStackTrace();
                Toast.makeText(getActivity().getBaseContext(),"json error",Toast.LENGTH_LONG).show();
                return;
            }

            if(objResponse.has("msg_registered"))
            {
                mRegistrationSuccess=true;
                mUsername = username.getText().toString();
                mPassword = password.getText().toString();
                //Toast.makeText(getActivity(),getString(R.string.error_reg_success),Toast.LENGTH_LONG).show();
            }
            else if(objResponse.has("usertaken"))
            {
                setToRed((TextView)getView().findViewById(R.id.textViewRegUsername));
                //Toast.makeText(getActivity(),getString(R.string.error_reg_usertaken),Toast.LENGTH_LONG).show();
            }
            else
            {
                //Toast.makeText(getActivity().getApplicationContext(),getString(R.string.error_reg_fail),Toast.LENGTH_LONG).show();
                if(objResponse.has("username")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegUsername));
                }
                if(objResponse.has("password")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegPass));
                }
                if(objResponse.has("passwordmatch")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegPassVerify));
                }
                if(objResponse.has("firstname")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegFirstName));
                }
                if(objResponse.has("lastname")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegLastName));
                }
                if(objResponse.has("email")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegEmail));
                }
                if(objResponse.has("address")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegStreetAddress));
                }
                if(objResponse.has("city")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegCity));
                }
                if(objResponse.has("state")){
                    setToRed((TextView) getView().findViewById(R.id.textViewRegState));
                }
                if(objResponse.has("zipcode")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegZIP));
                }
                /*
                if(objResponse.has("country")){
                    setToRed((TextView)getView().findViewById(R.id.textViewRegCountry));
                }
                */
            }
        }
    }

    protected void setToRed(final TextView textView)
    {
        textView.post(new Runnable() {
            @Override
            public void run() {
                textView.setTextColor(Color.RED);
            }
        });
    }

    protected void setToBlack()
    {
        ArrayList<TextView> views = new ArrayList<TextView>();

        views.add((TextView)getView().findViewById(R.id.textViewRegUsername));
        views.add((TextView)getView().findViewById(R.id.textViewRegPass));
        views.add((TextView)getView().findViewById(R.id.textViewRegPassVerify));
        views.add((TextView)getView().findViewById(R.id.textViewRegFirstName));
        views.add((TextView)getView().findViewById(R.id.textViewRegLastName));
        views.add((TextView)getView().findViewById(R.id.textViewRegEmail));
        views.add((TextView)getView().findViewById(R.id.textViewRegStreetAddress));
        views.add((TextView)getView().findViewById(R.id.textViewRegCity));
        views.add((TextView)getView().findViewById(R.id.textViewRegZIP));
        views.add((TextView) getView().findViewById(R.id.textViewRegState));

        for(int i=0;i<views.size();i++) {
            final TextView textView = views.get(i);
            textView.post(new Runnable() {
                @Override
                public void run() {
                    textView.setTextColor(Color.BLACK);
                }
            });
        }
    }
}
