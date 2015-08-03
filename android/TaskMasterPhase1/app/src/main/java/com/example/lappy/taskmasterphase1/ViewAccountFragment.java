package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.content.EntityIterator;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.provider.MediaStore;
import android.util.Base64;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.List;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link ViewAccountFragment.OnFragmentInteractionListener} interface
 * to handle interaction events.
 * Use the {@link ViewAccountFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class ViewAccountFragment extends Fragment {

    private static Bitmap mAvatarToUpload;

    private int AVATAR_SELECT = 18225;

    private ProgressBar mUploading;


    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    private OnFragmentInteractionListener mListener;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment ViewAccountFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static ViewAccountFragment newInstance(String param1, String param2) {
        ViewAccountFragment fragment = new ViewAccountFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    public ViewAccountFragment() {
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
        // Inflate the layout for this fragment
        View view = inflater.inflate(R.layout.fragment_view_account, container, false);
        final ImageView avatar = (ImageView)view.findViewById(R.id.imageViewViewAcctAvatar);

        mUploading = (ProgressBar)view.findViewById(R.id.progressBarViewAcctUploading);
        mUploading.setVisibility(View.INVISIBLE);

        Button uploadAvatar = (Button)view.findViewById(R.id.buttonViewAcctUploadAvatar);
        uploadAvatar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //Intent pickImage = new Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
                //startActivityForResult(pickImage, IMAGE_PICKER_SELECT);
                Intent pickAvatar = new Intent(Intent.ACTION_GET_CONTENT);
                pickAvatar.setType("image/*");
                Bitmap oldAvatar = mAvatarToUpload;
                startActivityForResult(pickAvatar, AVATAR_SELECT);
            }
        });

        return view;
    }


    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);/*
        try {
            mListener = (OnFragmentInteractionListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnFragmentInteractionListener");
        }*/
    }

    @Override
    public void onDetach() {
        super.onDetach();
        mListener = null;
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        if(requestCode == AVATAR_SELECT && resultCode == Activity.RESULT_OK)
        {
            if(data!=null && data.getData() != null)
            {
                try
                {
                    mAvatarToUpload = MediaStore.Images.Media.getBitmap(getView().getContext().getContentResolver(),data.getData());

                    ImageView avatar = (ImageView)getView().findViewById(R.id.imageViewViewAcctAvatar);
                    avatar.setImageBitmap(mAvatarToUpload);
                    avatar.invalidate();

                    new UploadAvatar().execute();
                }
                catch(Throwable t)
                {
                    t.printStackTrace();
                }
            }
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
    public interface OnFragmentInteractionListener {
        // TODO: Update argument type and name
        public void onFragmentInteraction(Uri uri);
    }

    protected class UploadAvatar extends AsyncTask<Void, Void, JSONObject>
    {
        @Override
        protected JSONObject doInBackground(Void... params) {
            return uploadAvatar();
        }

        @Override
        protected void onPreExecute() {
            mUploading.setVisibility(View.VISIBLE);
        }

        @Override
        protected void onPostExecute(JSONObject jsonObject) {
            mUploading.setVisibility(View.INVISIBLE);
        }

        protected JSONObject uploadAvatar()
        {
            try {
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_upload_avatar));
                ByteArrayOutputStream stream = new ByteArrayOutputStream();
                mAvatarToUpload.compress(Bitmap.CompressFormat.JPEG, 90, stream);
                byte[] byte_arr = stream.toByteArray();
                String imgStr = Base64.encodeToString(byte_arr, Base64.DEFAULT);
                List<NameValuePair> nvp = new ArrayList<NameValuePair>();
                nvp.add(new BasicNameValuePair("Img", imgStr));
                nvp.add(new BasicNameValuePair("UserID", Integer.toString(MainActivity.getmUserID())));
                System.err.println("tried with user id " + MainActivity.getmUserID());
                post.setEntity(new UrlEncodedFormEntity(nvp));
                HttpResponse resp = client.execute(post);
                HttpEntity entity = resp.getEntity();
                String decodedEntiy = EntityUtils.toString(entity);
                System.err.println(decodedEntiy);

                return new JSONObject(decodedEntiy);
            }
            catch(Throwable t)
            {
                t.printStackTrace();
            }
            return null;
        }
    }

}
