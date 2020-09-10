package com.mallchain.wallet.fragents;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;

import com.google.zxing.WriterException;
import com.mallchain.wallet.R;
import com.mallchain.wallet.activity.ImportKeystoreActivity;
import com.mallchain.wallet.utils.QRCodeUtil;

/**
 * Created by Administrator on 2018/8/13.
 */
public class ImportKeystoreScanFragment extends Fragment{
    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_importkeystoresan,null);
        final ImageView qrcode = (ImageView) rootView.findViewById(R.id.scan);
        qrcode.post(new Runnable() {
            @Override
            public void run() {
                try {
                    qrcode.setImageBitmap(QRCodeUtil.createQRCode(((ImportKeystoreActivity)getActivity()).keystore,qrcode.getMeasuredWidth()));
                } catch (WriterException e) {
                    e.printStackTrace();
                }
            }
        });
        return rootView;
    }
}
